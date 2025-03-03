<?php

namespace Modules\Vehicle\src\DTOs;

use Carbon\Carbon;

class ServiceHistoryData
{
    public function __construct(
        public readonly int $vehicleId,
        public readonly ?Carbon $serviceDate,
        public readonly string $description,
        public readonly ?float $cost = 0.0,
        public readonly ?string $serviceType = null,
        public readonly ?string $mechanic = null,
        public readonly ?string $notes = null,
        public readonly ?array $documents = []
    ) {}

    // Static method to create from array (useful when coming from requests)
    public static function fromArray(array $data): self
    {
        return new self(
            vehicleId: $data['vehicle_id'],
            serviceDate: isset($data['service_date']) ? Carbon::parse($data['service_date']) : null,
            description: $data['description'],
            cost: $data['cost'] ?? 0.0,
            serviceType: $data['service_type'] ?? null,
            mechanic: $data['mechanic'] ?? null,
            notes: $data['notes'] ?? null,
            documents: $data['documents'] ?? []
        );
    }

    // Convert to array (useful for repository operations)
    public function toArray(): array
    {
        return [
            'vehicle_id' => $this->vehicleId,
            'service_date' => $this->serviceDate,
            'description' => $this->description,
            'cost' => $this->cost,
            'service_type' => $this->serviceType,
            'mechanic' => $this->mechanic,
            'notes' => $this->notes
        ];
    }
}
