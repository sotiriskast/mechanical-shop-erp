<?php

namespace Modules\Vehicle\src\DTOs;

use Modules\Vehicle\src\Http\Requests\V1\CreateVehicleRequest;

class VehicleData
{
    public function __construct(
        public readonly string $licensePlate,
        public readonly ?string $vin,
        public readonly string $make,
        public readonly string $model,
        public readonly int $year,
        public readonly ?string $color,
        public readonly ?string $engineNumber,
        public readonly ?string $engineType,
        public readonly ?string $transmission,
        public readonly int $mileage,
        public readonly string $mileageUnit,
        public readonly ?string $notes,
        public readonly string $status,
        public readonly int $customerId,
        public readonly ?array $documents = []
    ) {}

    public static function fromRequest(CreateVehicleRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            licensePlate: $validated['license_plate'],
            vin: $validated['vin'] ?? null,
            make: $validated['make'],
            model: $validated['model'],
            year: (int) $validated['year'],
            color: $validated['color'] ?? null,
            engineNumber: $validated['engine_number'] ?? null,
            engineType: $validated['engine_type'] ?? null,
            transmission: $validated['transmission'] ?? null,
            mileage: (int) ($validated['mileage'] ?? 0),
            mileageUnit: $validated['mileage_unit'] ?? 'km',
            notes: $validated['notes'] ?? null,
            status: $validated['status'] ?? 'active',
            customerId: $validated['customer_id'],
            documents: $validated['documents'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'license_plate' => $this->licensePlate,
            'vin' => $this->vin,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
            'engine_number' => $this->engineNumber,
            'engine_type' => $this->engineType,
            'transmission' => $this->transmission,
            'mileage' => $this->mileage,
            'mileage_unit' => $this->mileageUnit,
            'notes' => $this->notes,
            'status' => $this->status,
            'customer_id' => $this->customerId
        ];
    }
}
