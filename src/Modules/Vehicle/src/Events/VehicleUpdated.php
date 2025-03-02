<?php

namespace Modules\Vehicle\src\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Vehicle\src\Models\Vehicle;

class VehicleUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Vehicle $vehicle
    ) {}
}
