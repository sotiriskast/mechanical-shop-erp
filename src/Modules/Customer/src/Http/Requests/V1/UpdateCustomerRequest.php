<?php
namespace Modules\Customer\src\Http\Requests\V1;
use Illuminate\Http\Request;
class UpdateCustomerRequest extends CreateCustomerRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['email'] = ['required', 'email', 'unique:customers,email,' . $this->customer->id];
        return $rules;
    }
}
