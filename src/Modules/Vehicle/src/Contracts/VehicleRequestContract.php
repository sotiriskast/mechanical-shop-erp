<?php

namespace Modules\Vehicle\src\Contracts;

use Modules\Vehicle\src\DTOs\VehicleData;

interface VehicleRequestContract
{
    public function toDTO(): VehicleData;
}
