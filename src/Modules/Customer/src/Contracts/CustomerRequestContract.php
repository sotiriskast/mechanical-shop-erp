<?php

namespace Modules\Customer\src\Contracts;

use Modules\Customer\src\DTOs\CustomerData;

interface CustomerRequestContract
{
    public function toDTO(): CustomerData;
}
