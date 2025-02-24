<?php

namespace Modules\Customer\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Customer\src\DTOs\CustomerData;
use Modules\Customer\src\Events\CustomerUpdated;
use Modules\Customer\src\Models\Customer;
use Modules\Customer\src\Repositories\CustomerRepository;
use Modules\Customer\src\Services\CustomerMediaService;
use Modules\Customer\src\Exceptions\CustomerException;

class UpdateCustomerAction
{
    public function __construct(
        private CustomerRepository $repository,
        private CustomerMediaService $mediaService
    ) {}

    public function execute(int $id, CustomerData $data): Customer
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $customer = $this->repository->find($id);

                if (!$customer) {
                    throw new CustomerException(trans('customers.errors.not_found'));
                }

                $customer = $this->repository->update($id, $data->toArray());

                if (!empty($data->documents)) {
                    $this->mediaService->attachDocuments($customer, $data->documents);
                }

                event(new CustomerUpdated($customer));

                return $customer;
            });
        } catch (CustomerException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CustomerException(
                trans('customers.errors.update_failed'),
                0,
                $e
            );
        }
    }
}
