<?php

namespace Modules\Vehicle\src\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'service_date' => ['required', 'date'],
            'service_type' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'mileage' => ['required', 'integer', 'min:0'],
            'mileage_unit' => ['nullable', 'string', 'in:km,mi'],
            'technician_name' => ['nullable', 'string', 'max:100'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'string', 'in:completed,scheduled,in_progress,cancelled'],
            'notes' => ['nullable', 'string'],
            'work_order_id' => ['nullable', 'integer'],
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
            'documents.*.max' => trans('vehicles.validation.document_size'),
            'documents.*.mimes' => trans('vehicles.validation.document_type'),
        ];
    }
}
