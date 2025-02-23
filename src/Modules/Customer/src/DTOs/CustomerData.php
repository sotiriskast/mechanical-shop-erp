<?php

namespace Modules\Customer\src\DTOs;

use Modules\Customer\src\Http\Requests\V1\CreateCustomerRequest;

class CustomerData
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly ?string $mobile,
        public readonly ?string $companyName,
        public readonly ?string $vatNumber,
        public readonly ?string $taxOffice,
        public readonly ?AddressData $billingAddress,
        public readonly ?AddressData $shippingAddress,
        public readonly bool $isActive = true,
        public readonly ?string $notes = null,
        public readonly ?array $documents = []
    ) {}

    public static function fromRequest(CreateCustomerRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            firstName: $validated['first_name'],
            lastName: $validated['last_name'],
            email: $validated['email'],
            phone: $validated['phone'] ?? null,
            mobile: $validated['mobile'] ?? null,
            companyName: $validated['company_name'] ?? null,
            vatNumber: $validated['vat_number'] ?? null,
            taxOffice: $validated['tax_office'] ?? null,
            billingAddress: isset($validated['billing_address']) ? AddressData::fromArray([
                'street' => $validated['billing_address'],
                'city' => $validated['city'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'country' => $validated['country'] ?? 'Cyprus'
            ]) : null,
            shippingAddress: isset($validated['shipping_address']) ? AddressData::fromArray([
                'street' => $validated['shipping_address'],
                'country' => $validated['country'] ?? 'Cyprus'
            ]) : null,
            isActive: $validated['is_active'] ?? true,
            notes: $validated['notes'] ?? null,
            documents: $validated['documents'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'company_name' => $this->companyName,
            'vat_number' => $this->vatNumber,
            'tax_office' => $this->taxOffice,
            'billing_address' => $this->billingAddress?->street,
            'shipping_address' => $this->shippingAddress?->street,
            'city' => $this->billingAddress?->city,
            'postal_code' => $this->billingAddress?->postalCode,
            'country' => $this->billingAddress?->country ?? 'Cyprus',
            'is_active' => $this->isActive,
            'notes' => $this->notes
        ];
    }
}
