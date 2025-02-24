<?php

namespace Modules\Customer\src\Services;

use Modules\Core\src\Abstracts\BaseService;
use Modules\Customer\src\Actions\CreateCustomerAction;
use Modules\Customer\src\Actions\UpdateCustomerAction;
use Modules\Customer\src\Actions\DeleteCustomerAction;
use Modules\Customer\src\DTOs\CustomerData;
use Modules\Customer\src\Models\Customer;
use Modules\Customer\src\Exceptions\CustomerException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Customer\src\Repositories\CustomerRepository;

class CustomerService extends BaseService
{
    public function __construct(
        CustomerRepository   $repository,
        private CreateCustomerAction $createAction,
        private UpdateCustomerAction $updateAction,
        private DeleteCustomerAction $deleteAction
    )
    {
        parent::__construct($repository);
    }

    public function list(array $params = []): LengthAwarePaginator
    {
        try {
            return $this->repository->searchCustomers($params);
        } catch (\Exception $e) {
            throw new CustomerException(
                trans('customers.errors.fetch_failed'),
                0,
                $e
            );
        }
    }

    public function create(array $data): Customer
    {
        try {
            $customerData = CustomerData::fromRequest($data);
            return $this->createAction->execute($customerData);
        } catch (\Exception $e) {
            throw new CustomerException(
                trans('customers.errors.create_failed'),
                0,
                $e
            );
        }
    }

    public function update(int $id, array $data): Customer
    {
        try {
            $customerData = CustomerData::fromRequest($data);
            return $this->updateAction->execute($id, $customerData);
        } catch (\Exception $e) {
            throw new CustomerException(
                trans('customers.errors.update_failed'),
                0,
                $e
            );
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->deleteAction->execute($id);
        } catch (\Exception $e) {
            throw new CustomerException(
                trans('customers.errors.delete_failed'),
                0,
                $e
            );
        }
    }

    public function findByCode(string $code): ?Customer
    {
        try {
            return $this->repository->findByCode($code);
        } catch (\Exception $e) {
            throw new CustomerException(
                trans('customers.errors.fetch_failed'),
                0,
                $e
            );
        }
    }

    public function getActiveCustomers(): Collection
    {
        try {
            return $this->repository->getActiveCustomers();
        } catch (\Exception $e) {
            throw new CustomerException(
                trans('customers.errors.fetch_failed'),
                0,
                $e
            );
        }
    }
}
