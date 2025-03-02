<?php

namespace Modules\Vehicle\src\DTOs;

use Modules\Vehicle\src\Http\Requests\V1\CreateServiceHistoryRequest;

class ServiceHistoryData
{
    public function __construct(
        public readonly int $vehicleId,
        public readonly string $serviceDate,
        public readonly string $serviceType,
        public readonly string $description,
        public readonly int $mileage,
        public readonly string $mileageUnit,
        public readonly ?string $technicianName,
        public readonly float $cost,
        public readonly string $status,
        public readonly ?string $notes,
        public readonly ?int $workOrderId = null,
        public readonly ?array $documents = []
    ) {}

    public static function fromRequest(CreateServiceHistoryRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            vehicleId: $validated['vehicle_id'],
            serviceDate: $validated['service_date'],
            serviceType: $validated['service_type'],
            description: $validated['description'],
            mileage: (int) $validated['mileage'],
            mileageUnit: $validated['mileage_unit'] ?? 'km',
            technicianName: $validated['technician_name'] ?? null,
            cost: (float) ($validated['cost'] ?? 0),
            status: $validated['status'] ?? 'completed',
            notes: $validated['notes'] ?? null,
            workOrderId: $validated['work_order_id'] ?? null,
            documents: $validated['documents'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'vehicle_id' => $this->vehicleId,
            'service_date' => $this->serviceDate,
            'service_type' => $this->serviceType,
            'description' => $this->description,
            'mileage' => $this->mileage,
            'mileage_unit' => $this->mileageUnit,
            'technician_name' => $this->technicianName,
            'cost' => $this->cost,
            'status' => $this->status,
            'notes' => $this->notes,
            'work_order_id' => $this->workOrderId
        ];
    }
}
