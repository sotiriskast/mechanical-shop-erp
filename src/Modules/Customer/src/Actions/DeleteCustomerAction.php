<?php

namespace Modules\Customer\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Customer\src\Events\CustomerDeleted;
use Modules\Customer\src\Exceptions\CustomerException;
use Modules\Customer\src\Repositories\CustomerRepository;

class DeleteCustomerAction
{
    public function __construct(
        private CustomerRepository $repository
    )
    {
    }

    public function execute(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $customer = $this->repository->find($id);

                if (!$customer) {
                    throw new CustomerException(trans('customers.errors.not_found'));
                }

                if ($this->hasRelatedRecords($customer)) {
                    throw new CustomerException(trans('customers.errors.has_related_records'));
                }

                $deleted = $this->repository->delete($id);

                if ($deleted) {
                    event(new CustomerDeleted($customer));
                }

                return $deleted;
            });
        } catch (CustomerException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CustomerException(
                trans('customers.errors.delete_failed'),
                0,
                $e
            );
        }
    }

    private function hasRelatedRecords($customer): bool
    {
        return $customer->vehicles()->exists() ||
            $customer->workOrders()->exists() ||
            $customer->invoices()->exists();
    }
}
