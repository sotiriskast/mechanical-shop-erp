<?php

namespace Modules\Customer\src\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Customer\src\Exceptions\CustomerException;
use Modules\Customer\src\Repositories\CustomerRepository;

class CustomerSearchService
{
    public function __construct(
        private readonly CustomerRepository $repository
    )
    {
    }

    public function search(array $criteria): LengthAwarePaginator
    {
        try {
            return $this->repository->searchCustomers($this->prepareCriteria($criteria));
        } catch (\Exception $e) {
            throw new CustomerException(
                trans('customers.errors.search_failed'),
                0,
                $e
            );
        }
    }

    private function prepareCriteria(array $criteria): array
    {
        return array_filter([
            'search' => $criteria['q'] ?? null,
            'is_active' => $criteria['status'] ?? null,
            'sort_by' => $criteria['sort'] ?? 'created_at',
            'sort_direction' => $criteria['direction'] ?? 'desc',
            'per_page' => $criteria['per_page'] ?? 15,
            'with' => $criteria['include'] ?? [],
        ]);
    }
}
