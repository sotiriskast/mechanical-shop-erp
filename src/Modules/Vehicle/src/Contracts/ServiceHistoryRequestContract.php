<?php

namespace Modules\Vehicle\src\Contracts;

use Modules\Vehicle\src\DTOs\ServiceHistoryData;

interface ServiceHistoryRequestContract
{
    public function toDTO(): ServiceHistoryData;
}
