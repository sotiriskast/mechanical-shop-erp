<?php

namespace Modules\Customer\src\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class CyprusVatNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!empty($value) && preg_match('/^CY\d{5,9}[A-Z]$/', $value) !== 1) {
            $fail(trans('customers.validation.vat_number_format'));
        }
    }
}
