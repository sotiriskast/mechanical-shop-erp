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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'vehicle' => $this->when($this->relationLoaded('vehicle'),
                fn() => new VehicleResource($this->vehicle)
            ),
            'media' => $this->when($this->relationLoaded('media'), function() {
                return $this->media->map(function($media) {
                    return [
                        'id' => $media->id,
                        'file_name' => $media->file_name,
                        'mime_type' => $media->mime_type,
                        'size' => $media->size,
                        'url' => $media->getUrl(),
                        'extension' => $media->extension
                    ];
                });
            }),
            'translations' => [
                'fields' => trans('vehicles.service_history.fields', [], $locale),
                'messages' => trans('vehicles.service_history.messages', [], $locale),
                'status' => trans('vehicles.service_history.status', [], $locale)
            ],
        ];
    }
}
