<?php

namespace Modules\Vehicle\src\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Vehicle\src\Exceptions\VehicleException;
use Modules\Vehicle\src\Repositories\VehicleRepository;

readonly class VehicleSearchService
{
    public function __construct(
        private VehicleRepository $repository
    ) {}

    public function search(array $criteria): LengthAwarePaginator
    {
        try {
            return $this->repository->searchVehicles($this->prepareCriteria($criteria));
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.search_failed'),
                0,
                $e
            );
        }
    }

    private function prepareCriteria(array $criteria): array
    {
        return array_filter([
            'search' => $criteria['q'] ?? null,
            'customer_id' => $criteria['customer_id'] ?? null,
            'status' => $criteria['status'] ?? null,
            'make' => $criteria['make'] ?? null,
            'model' => $criteria['model'] ?? null,
            'year' => $criteria['year'] ?? null,
            'sort_by' => $criteria['sort'] ?? 'created_at',
            'sort_direction' => $criteria['direction'] ?? 'desc',
            'per_page' => $criteria['per_page'] ?? 15,
            'with' => $criteria['include'] ?? ['customer'],
        ]);
    }
}
