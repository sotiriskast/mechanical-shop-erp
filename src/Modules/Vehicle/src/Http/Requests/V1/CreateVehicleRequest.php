<?php
// VehicleRequest classes

namespace Modules\Vehicle\src\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Vehicle\src\DTOs\VehicleData;

class CreateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Use middleware for authorization
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'make' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y'))],
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate'],
            'vin' => ['nullable', 'string', 'max:17', 'unique:vehicles,vin'],
            'color' => ['nullable', 'string', 'max:30'],
            'engine_type' => ['nullable', 'string', 'max:30'],
            'transmission' => ['nullable', 'string', 'max:20'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'registration_date' => ['nullable', 'date'],
            'mot_due_date' => ['nullable', 'date'],
            'insurance_expiry_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'documents.*' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240'
            ],
        ];
    }

    public function toDTO(): VehicleData
    {
        return VehicleData::fromArray($this->validated());
    }
}


