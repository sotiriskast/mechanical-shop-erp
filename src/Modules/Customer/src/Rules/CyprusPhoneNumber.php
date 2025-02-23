<?php

namespace Modules\Customer\src\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class CyprusPhoneNumber implements ValidationRule
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
        if (!empty($value) && preg_match('/^\+357\d{8}$/', $value) !== 1) {
            $fail(trans('customers.validation.phone_number_format'));
        }
    }
}
