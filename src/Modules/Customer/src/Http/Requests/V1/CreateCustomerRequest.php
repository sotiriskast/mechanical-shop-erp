<?php

namespace Modules\Customer\src\Http\Requests\V1;
use Illuminate\Http\Request;
use Modules\Customer\src\Rules\CyprusPhoneNumber;
use Modules\Customer\src\Rules\CyprusVatNumber;

class CreateCustomerRequest
{
    public function authorize(): bool
    {
        return true; // Add authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'phone' => ['nullable', new CyprusPhoneNumber],
            'mobile' => ['nullable', new CyprusPhoneNumber],
            'company_name' => ['nullable', 'string', 'max:255'],
            'vat_number' => ['nullable', new CyprusVatNumber],
            'tax_office' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['nullable', 'string'],
            'shipping_address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'regex:/^\d{4}$/'],
            'country' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
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
            'documents.*.max' => trans('customers.validation.document_size'),
            'documents.*.mimes' => trans('customers.validation.document_type'),

            'vat_number.regex' => 'The VAT number must be in Cyprus format (e.g., CY123456789X)',
            'phone.regex' => 'The phone number must be in Cyprus format (+35712345678)',
            'postal_code.regex' => 'The postal code must be in Cyprus format (4 digits)',
        ];
    }
}
