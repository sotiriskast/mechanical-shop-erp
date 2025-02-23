<?php

namespace Modules\Customer\src\DTOs;

class AddressData
{
    public function __construct(
        public readonly string $street,
        public readonly ?string $city = null,
        public readonly ?string $postalCode = null,
        public readonly string $country = 'Cyprus'
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            street: $data['street'],
            city: $data['city'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            country: $data['country'] ?? 'Cyprus'
        );
    }
}
