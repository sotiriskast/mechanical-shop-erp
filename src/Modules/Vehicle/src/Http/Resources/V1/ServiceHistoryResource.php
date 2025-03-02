<?php

namespace Modules\Vehicle\src\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'vehicle_id' => $this->vehicle_id,
            'service_date' => $this->service_date,
            'service_type' => $this->service_type,
            'description' => $this->description,
            'mileage' => $this->mileage,
            'mileage_unit' => $this->mileage_unit,
            'technician_name' => $this->technician_name,
            'cost' => $this->cost,
            'status' => $this->status,
            'notes' => $this->notes,
            'work_order_id' => $this->work_order_id,
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'translations' => [
                'fields' => trans('vehicles.fields', [], $locale),
                'messages' => trans('vehicles.messages', [], $locale),
            ],
        ];
    }
}
