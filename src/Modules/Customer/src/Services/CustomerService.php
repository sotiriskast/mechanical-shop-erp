<?php


namespace Modules\Customer\src\Services;

use Illuminate\Support\Facades\Log;
use Modules\Core\src\Abstracts\BaseService;
use Modules\Customer\src\Exceptions\CustomerException;
use Modules\Customer\src\Repositories\CustomerRepository;
use Illuminate\Support\Facades\DB;
use Modules\Customer\src\Events\CustomerCreated;
use Modules\Customer\src\Events\CustomerUpdated;

class CustomerService extends BaseService
{
    public function __construct(
        protected CustomerRepository $customerRepository
    ) {
        parent::__construct($customerRepository);
    }

    public function list(array $params = [])
    {
        try {
            return $this->customerRepository->searchCustomers($params);
        } catch (\Exception $e) {
            Log::error('Failed to fetch customers list', [
                'error' => $e->getMessage(),
                'params' => $params
            ]);

            throw new CustomerException(
                trans('customers.errors.fetch_failed'),
                0,
                $e
            );
        }
    }

    public function create(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $customer = $this->customerRepository->create($data);

                if (isset($data['documents'])) {
                    foreach ($data['documents'] as $document) {
                        $customer->addMedia($document)
                            ->toMediaCollection('documents');
                    }
                }

                event(new CustomerCreated($customer));

                return $customer;
            });
        } catch (\Exception $e) {
            Log::error('Failed to create customer', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            throw new CustomerException(
                trans('customers.errors.create_failed'),
                0,
                $e
            );
        }
    }

    public function update(int $id, array $data)
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $customer = $this->customerRepository->find($id);

                if (!$customer) {
                    throw new CustomerException(trans('customers.errors.not_found'));
                }

                $customer = $this->customerRepository->update($id, $data);

                if (isset($data['documents'])) {
                    foreach ($data['documents'] as $document) {
                        $customer->addMedia($document)
                            ->toMediaCollection('documents');
                    }
                }

                event(new CustomerUpdated($customer));

                return $customer;
            });
        } catch (CustomerException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to update customer', [
                'error' => $e->getMessage(),
                'customer_id' => $id,
                'data' => $data
            ]);

            throw new CustomerException(
                trans('customers.errors.update_failed'),
                0,
                $e
            );
        }
    }

    public function delete(int $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $customer = $this->customerRepository->find($id);

                if (!$customer) {
                    throw new CustomerException(trans('customers.errors.not_found'));
                }

                // Check if customer can be deleted (no related records)
                if ($this->hasRelatedRecords($customer)) {
                    throw new CustomerException(trans('customers.errors.has_related_records'));
                }

                $this->customerRepository->delete($id);

                event(new CustomerDeleted($customer));

                return true;
            });
        } catch (CustomerException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to delete customer', [
                'error' => $e->getMessage(),
                'customer_id' => $id
            ]);

            throw new CustomerException(
                trans('customers.errors.delete_failed'),
                0,
                $e
            );
        }
    }

    public function findByCode(string $code)
    {
        try {
            $customer = $this->customerRepository->findByCode($code);

            if (!$customer) {
                throw new CustomerException(trans('customers.errors.not_found'));
            }

            return $customer;
        } catch (CustomerException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to find customer by code', [
                'error' => $e->getMessage(),
                'code' => $code
            ]);

            throw new CustomerException(
                trans('customers.errors.fetch_failed'),
                0,
                $e
            );
        }
    }

    public function getActiveCustomers()
    {
        try {
            return $this->customerRepository->getActiveCustomers();
        } catch (\Exception $e) {
            Log::error('Failed to fetch active customers', [
                'error' => $e->getMessage()
            ]);

            throw new CustomerException(
                trans('customers.errors.fetch_failed'),
                0,
                $e
            );
        }
    }

    protected function hasRelatedRecords($customer): bool
    {
        // Check for related vehicles
        if ($customer->vehicles()->count() > 0) {
            return true;
        }

        // Check for related work orders
        if ($customer->workOrders()->count() > 0) {
            return true;
        }

        // Check for related invoices
        if ($customer->invoices()->count() > 0) {
            return true;
        }

        return false;
    }
}
