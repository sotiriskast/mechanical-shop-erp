<?php

namespace Modules\Vehicle\src\Http\Requests\V1;

use Modules\Vehicle\src\Contracts\ServiceHistoryRequestContract;
use Modules\Vehicle\src\DTOs\ServiceHistoryData;

class UpdateServiceHistoryRequest extends CreateServiceHistoryRequest implements ServiceHistoryRequestContract
{
    public function rules(): array
    {
        $rules = parent::rules();

        // No need to modify any rules for updating service history
        // as there aren't any unique constraints to worry about

        return $rules;
    }
}
