<?php
// ServiceHistoryRequest classes

namespace Modules\Vehicle\src\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Vehicle\src\DTOs\ServiceHistoryData;

class CreateServiceHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Use middleware for authorization
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'service_date' => ['required', 'date'],
            'description' => ['required', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'service_type' => ['nullable', 'string', 'max:50'],
            'mechanic' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'documents.*' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240'
            ],
        ];
    }

    public function toDTO(): ServiceHistoryData
    {
        return ServiceHistoryData::fromArray($this->validated());
    }
}
