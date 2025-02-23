<?php

namespace Modules\Customer\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Customer\src\DTOs\CustomerData;
use Modules\Customer\src\Events\CustomerCreated;
use Modules\Customer\src\Models\Customer;
use Modules\Customer\src\Services\CustomerMediaService;
use Modules\Customer\src\Contracts\CustomerRepositoryInterface;
use Modules\Customer\src\Exceptions\CustomerException;

class CreateCustomerAction
{
    public function __construct(
        private CustomerRepositoryInterface $repository,
        private CustomerMediaService $mediaService
    ) {}

    public function execute(CustomerData $data): Customer
    {
        try {
            return DB::transaction(function () use ($data) {
                $customer = $this->repository->create($data->toArray());

                if (!empty($data->documents)) {
                    $this->mediaService->attachDocuments($customer, $data->documents);
                }

                event(new CustomerCreated($customer));

                return $customer;
            });
        } catch (\Exception $e) {
            throw new CustomerException(
                trans('customers.errors.create_failed'),
                0,
                $e
            );
        }
    }
}
