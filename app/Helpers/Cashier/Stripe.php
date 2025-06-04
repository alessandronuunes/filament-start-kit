<?php

declare(strict_types=1);

namespace App\Helpers\Cashier;

use App\Models\Team;

use function App\Support\tenant;

use Filament\Pages\Dashboard;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Laravel\Cashier\SubscriptionBuilder;

readonly class Stripe
{
    private function __construct(
        private ?bool $hasDiscount,
        private ?int $discount,
        private ?bool $allowPromotionCodes,
        private ?bool $hasGenericTrial,
        private ?int $trialDays,
        private Collection $billedPeriods,
        private Collection $plans
    ) {
    }

    public static function fromConfig(): self
    {
        return new self(
            hasDiscount: config('stripe.has_discount'),
            discount: config('stripe.discount'),
            allowPromotionCodes: config('stripe.allow_promotion_codes'),
            hasGenericTrial: config('stripe.has_generic_trial'),
            trialDays: config('stripe.trial_days'),
            billedPeriods: collect(config('stripe.billed_periods')),
            plans: collect(config('stripe.plans')),
        );
    }

    /**
     * @return Collection<Plan>
     */
    public function plans(): Collection
    {
        return $this->plans->map(fn (array $plan, string $key) => Plan::fromArray($plan, $key));
    }

    public function price(string $type, string $period): ?Price
    {
        /** @var Plan|null $plan */
        $plan = $this->plans()->first(fn (Plan $plan) => $plan->type() === $type);

        return $plan?->prices()->first(fn (Price $price) => $price->period() === $period);
    }

    public function hasDiscount(): bool
    {
        return boolval($this->hasDiscount);
    }

    public function discount(): ?int
    {
        return $this->discount;
    }

    public function allowPromotionCodes(): bool
    {
        return boolval($this->allowPromotionCodes);
    }

    public function hasGenericTrial(): bool
    {
        return boolval($this->hasGenericTrial);
    }

    public function trialDays(): ?int
    {
        return $this->trialDays;
    }

    public function billedPeriods(): Collection
    {
        return $this->billedPeriods;
    }

    public function checkoutUrl(string $priceId): void
    {
        /** @var Plan|null $plan */
        $plan = $this->plans()->first(fn (Plan $plan) => $plan->prices()->contains(fn (Price $price) => $price->id() === $priceId));

        if (! $plan) {
            throw new InvalidArgumentException('Plan not configured');
        }

        /** @var Price|null $price */
        $price = $plan->prices()->firstWhere('id', $priceId);

        if (! $price) {
            throw new InvalidArgumentException("Price not found: {$priceId}");
        }

        tenant(Team::class)->newSubscription($plan->type(), $price->id())
            ->when(
                ! $this->hasGenericTrial() && filled($this->trialDays()),
                fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->trialDays($this->trialDays()),
            )
            ->when(
                $this->allowPromotionCodes(),
                fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->allowPromotionCodes(),
            )
            ->checkout([
                'success_url' => Dashboard::getUrl(),
                'cancel_url' => Dashboard::getUrl(),
            ])
            ->redirect();
    }
}
