<?php

declare(strict_types=1);

namespace App\Helpers\Cashier;

use Illuminate\Support\Collection;

readonly class Plan
{
    private function __construct(
        public string $type,
        public string $name,
        public string $shortDescription,
        public string $productId,
        public Collection $rawPrices,
        public Collection $features
    ) {
    }

    public static function fromArray(array $data, string $key): self
    {
        return new self(
            type: $data['type'] ?? $key,
            name: $data['name'],
            shortDescription: $data['short_description'],
            productId: $data['product_id'],
            rawPrices: collect($data['prices']),
            features: collect($data['features'])
        );
    }

    public function type(): string
    {
        return $this->type;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function shortDescription(): string
    {
        return $this->shortDescription;
    }

    public function productId(): string
    {
        return $this->productId;
    }

    /**
     * @return Collection<Price>
     */
    public function prices(): Collection
    {
        return $this->rawPrices->map(fn (array $price, string $key) => Price::fromArray($price, $key));
    }

    public function features(): Collection
    {
        return $this->features;
    }
}
