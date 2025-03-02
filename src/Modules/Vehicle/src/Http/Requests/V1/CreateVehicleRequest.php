<?php

namespace Modules\Vehicle\src\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Vehicle\src\Rules\CyprusLicensePlate;

class CreateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_plate' => ['required', 'string', 'max:10', 'unique:vehicles,license_plate', new CyprusLicensePlate],
            'vin' => ['nullable', 'string', 'max:17', 'unique:vehicles,vin'],
            'make' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color' => ['nullable', 'string', 'max:30'],
            'engine_number' => ['nullable', 'string', 'max:30'],
            'engine_type' => ['nullable', 'string', 'max:30'],
            'transmission' => ['nullable', 'string', 'max:30'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'mileage_unit' => ['nullable', 'string', 'in:km,mi'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:active,inactive,sold'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'documents.*' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'license_plate.unique' => trans('vehicles.validation.license_plate_unique'),
            'vin.unique' => trans('vehicles.validation.vin_unique'),
            'documents.*.max' => trans('vehicles.validation.document_size'),
            'documents.*.mimes' => trans('vehicles.validation.document_type'),
        ];
    }
}
