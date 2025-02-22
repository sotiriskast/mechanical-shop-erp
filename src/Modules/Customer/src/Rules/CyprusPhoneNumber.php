<?php

namespace Modules\Customer\src\Rules;

use Illuminate\Contracts\Validation\Rule;

class CyprusPhoneNumber implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return true;
        }

        return preg_match('/^\+357\d{8}$/', $value) === 1;
    }

    public function message(): string
    {
        return trans('customers.validation.phone_number_format');
    }
}
