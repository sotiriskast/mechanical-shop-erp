<?php

namespace Modules\Vehicle\src\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Core\src\Abstracts\BaseRepository;
use Modules\Vehicle\src\Models\ServiceHistory;

class ServiceHistoryRepository extends BaseRepository
{
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(ServiceHistory $model)
    {
        parent::__construct($model);
    }

    public function find(int $id): ?ServiceHistory
    {
        return $this->model->find($id);
    }

    public function create(array $data): ServiceHistory
    {
        $serviceHistory = parent::create($data);
        $this->invalidateCache($serviceHistory->vehicle_id);
        return $serviceHistory;
    }

    public function update(int $id, array $data): ServiceHistory
    {
        $serviceHistory = $this->find($id);
        $vehicleId = $serviceHistory->vehicle_id;

        $serviceHistory = parent::update($id, $data);
        $this->invalidateCache($vehicleId);

        return $serviceHistory;
    }

    public function delete(int $id): bool
    {
        $serviceHistory = $this->find($id);
        $vehicleId = $serviceHistory->vehicle_id;

        $result = parent::delete($id);
        $this->invalidateCache($vehicleId);

        return $result;
    }

    public function getVehicleServiceHistory(int $vehicleId): Collection
    {
        return Cache::remember("vehicle:{$vehicleId}:service-history", self::CACHE_TTL, function () use ($vehicleId) {
            return $this->model->where('vehicle_id', $vehicleId)
                ->orderBy('service_date', 'desc')
                ->get();
        });
    }

    public function searchServiceHistory(array $params): LengthAwarePaginator
    {
        $cacheKey = 'service-history:search:' . md5(serialize($params));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($params) {
            $query = $this->model->query();

            if (isset($params['search'])) {
                $search = $params['search'];
                $query = $this->applySearchCriteria($query, $search);
            }

            $query = $this->applyFilters($query, $params);
            $query = $this->applySorting($query, $params);

            if (isset($params['with'])) {
                $query->with($params['with']);
            }

            return $query->paginate($params['per_page'] ?? 15);
        });
    }

    private function applySearchCriteria(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('service_type', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('technician_name', 'like', "%{$search}%");
        });
    }

    private function applyFilters(Builder $query, array $params): Builder
    {
        if (isset($params['vehicle_id'])) {
            $query->where('vehicle_id', $params['vehicle_id']);
        }

        if (isset($params['service_type'])) {
            $query->where('service_type', $params['service_type']);
        }

        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['service_date_from'])) {
            $query->where('service_date', '>=', $params['service_date_from']);
        }

        if (isset($params['service_date_to'])) {
            $query->where('service_date', '<=', $params['service_date_to']);
        }

        return $query;
    }

    private function applySorting(Builder $query, array $params): Builder
    {
        $sortField = $params['sort_by'] ?? 'service_date';
        $sortDirection = $params['sort_direction'] ?? 'desc';

        return $query->orderBy($sortField, $sortDirection);
    }

    private function invalidateCache(?int $vehicleId = null): void
    {
        $keys = [
            'service-history:recent',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        if ($vehicleId) {
            Cache::forget("vehicle:{$vehicleId}:service-history");
        }
    }
}
