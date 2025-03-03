<?php

namespace Modules\Vehicle\src\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Modules\Vehicle\src\Contracts\VehicleRequestContract;
use Modules\Vehicle\src\DTOs\VehicleData;

class UpdateVehicleRequest extends CreateVehicleRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        // Update unique rules to ignore the current vehicle
        $rules['license_plate'] = ['required', 'string', 'max:20', 'unique:vehicles,license_plate,' . $this->route('vehicle')->id];
        $rules['vin'] = ['nullable', 'string', 'max:17', 'unique:vehicles,vin,' . $this->route('vehicle')->id];

        return $rules;
    }
}
