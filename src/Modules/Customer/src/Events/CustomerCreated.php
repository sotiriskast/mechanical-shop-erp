<?php

namespace Modules\Customer\src\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Customer\src\Models\Customer;

class CustomerCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Customer $customer
    ) {}
}
