<?php

declare(strict_types=1);

namespace App\Helpers\Cashier;

readonly class Price
{
    private function __construct(
        public string $period,
        public string $id,
        public int $price
    ) {
    }

    public static function fromArray(array $data, string $key): self
    {
        return new self(
            period: $data['period'] ?? $key,
            id: $data['id'],
            price: $data['price']
        );
    }

    public function period(): string
    {
        return $this->period;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function price(): int
    {
        return $this->price;
    }
}
