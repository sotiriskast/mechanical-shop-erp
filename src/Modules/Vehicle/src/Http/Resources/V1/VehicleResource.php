<?php

namespace Modules\Vehicle\src\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\src\Http\Resources\V1\CustomerResource;

class VehicleResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'code' => $this->code,
            'license_plate' => $this->license_plate,
            'vin' => $this->vin,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
            'engine_number' => $this->engine_number,
            'engine_type' => $this->engine_type,
            'transmission' => $this->transmission,
            'mileage' => $this->mileage,
            'mileage_unit' => $this->mileage_unit,
            'notes' => $this->notes,
            'status' => $this->status,
            'full_name' => $this->full_name,
            'customer_id' => $this->customer_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'service_history_count' => $this->whenCounted('serviceHistory'),
            'service_history' => ServiceHistoryResource::collection($this->whenLoaded('serviceHistory')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'translations' => [
                'fields' => trans('vehicles.fields', [], $locale),
                'messages' => trans('vehicles.messages', [], $locale),
            ],
        ];
    }
}
