<?php

namespace Modules\Vehicle\src\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class CyprusLicensePlate implements ValidationRule
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
        // Cyprus license plate format: ABC123 (3 letters followed by 3 numbers)
        // Or older format: A123 (1 letter followed by 3 numbers)
        if (!empty($value) && !preg_match('/^[A-Z]{1,3}\d{3,4}$/', $value)) {
            $fail(trans('vehicles.validation.license_plate_format'));
        }
    }
}
