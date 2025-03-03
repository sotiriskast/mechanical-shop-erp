<?php

namespace Modules\Vehicle\src\DTOs;

use Carbon\Carbon;

class VehicleData
{
    public function __construct(
        public readonly int $customerId,
        public readonly string $make,
        public readonly string $model,
        public readonly ?int $year,
        public readonly string $licensePlate,
        public readonly ?string $vin = null,
        public readonly ?string $color = null,
        public readonly ?string $engineType = null,
        public readonly ?string $transmission = null,
        public readonly ?int $mileage = null,
        public readonly ?Carbon $registrationDate = null,
        public readonly ?Carbon $motDueDate = null,
        public readonly ?Carbon $insuranceExpiryDate = null,
        public readonly ?string $notes = null,
        public readonly ?array $documents = []
    ) {}

    // Static method to create from array (useful when coming from requests)
    public static function fromArray(array $data): self
    {
        return new self(
            customerId: $data['customer_id'],
            make: $data['make'],
            model: $data['model'],
            year: $data['year'] ?? null,
            licensePlate: $data['license_plate'],
            vin: $data['vin'] ?? null,
            color: $data['color'] ?? null,
            engineType: $data['engine_type'] ?? null,
            transmission: $data['transmission'] ?? null,
            mileage: $data['mileage'] ?? null,
            registrationDate: isset($data['registration_date']) ? Carbon::parse($data['registration_date']) : null,
            motDueDate: isset($data['mot_due_date']) ? Carbon::parse($data['mot_due_date']) : null,
            insuranceExpiryDate: isset($data['insurance_expiry_date']) ? Carbon::parse($data['insurance_expiry_date']) : null,
            notes: $data['notes'] ?? null,
            documents: $data['documents'] ?? []
        );
    }

    // Convert to array (useful for repository operations)
    public function toArray(): array
    {
        return [
            'customer_id' => $this->customerId,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'license_plate' => $this->licensePlate,
            'vin' => $this->vin,
            'color' => $this->color,
            'engine_type' => $this->engineType,
            'transmission' => $this->transmission,
            'mileage' => $this->mileage,
            'registration_date' => $this->registrationDate,
            'mot_due_date' => $this->motDueDate,
            'insurance_expiry_date' => $this->insuranceExpiryDate,
            'notes' => $this->notes
        ];
    }
}
