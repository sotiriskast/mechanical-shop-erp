<?php

namespace Modules\Customer\src\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'code' => $this->code,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'company_name' => $this->company_name,
            'vat_number' => $this->vat_number,
            'tax_office' => $this->tax_office,
            'billing_address' => $this->billing_address,
            'shipping_address' => $this->shipping_address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
//            'vehicles_count' => $this->whenCounted('vehicles'),
//            'vehicles' => VehicleResource::collection($this->whenLoaded('vehicles')),
            'translations' => [
                'fields' => trans('customers.fields', [], $locale),
                'messages' => trans('customers.messages', [], $locale),
            ],
        ];
    }
}
