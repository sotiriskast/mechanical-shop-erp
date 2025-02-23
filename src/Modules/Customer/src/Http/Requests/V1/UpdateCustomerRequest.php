<?php
namespace Modules\Customer\src\Http\Requests\V1;
class UpdateCustomerRequest extends CreateCustomerRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
