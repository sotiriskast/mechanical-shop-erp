<?php
namespace Modules\Customer\src\Rules;

use Illuminate\Contracts\Validation\Rule;

class CyprusVatNumber implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return true;
        }

        return preg_match('/^CY\d{9}[A-Z]$/', $value) === 1;
    }

    public function message(): string
    {
        return trans('customers.validation.vat_number_format');
    }
}
