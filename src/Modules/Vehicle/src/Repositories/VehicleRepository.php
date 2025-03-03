<?php

namespace Modules\Vehicle\src\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Core\src\Abstracts\BaseRepository;
use Modules\Vehicle\src\Models\Vehicle;

class VehicleRepository extends BaseRepository
{
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(Vehicle $model)
    {
        parent::__construct($model);
    }

    public function find(int $id): ?Vehicle
    {
        return $this->model->find($id);
    }

    public function create(array $data): Vehicle
    {
        $vehicle = parent::create($data);
        $this->invalidateCache();
        return $vehicle;
    }

    public function update(int $id, array $data): Vehicle
    {
        $vehicle = parent::update($id, $data);
        $this->invalidateCache();
        return $vehicle;
    }

    public function delete(int $id): bool
    {
        $result = parent::delete($id);
        $this->invalidateCache();
        return $result;
    }

    public function findByLicensePlate(string $licensePlate): ?Vehicle
    {
        return $this->model->where('license_plate', $licensePlate)->first();
    }

    public function findByVIN(string $vin): ?Vehicle
    {
        return $this->model->where('vin', $vin)->first();
    }

    public function getActiveVehicles(): Collection
    {
        return Cache::remember('vehicles:active', self::CACHE_TTL, function () {
            return $this->model->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();
        });
    }

    public function getVehiclesByCustomer(int $customerId): Collection
    {
        return Cache::remember("vehicles:customer:{$customerId}", self::CACHE_TTL, function () use ($customerId) {
            return $this->model->where('customer_id', $customerId)
                ->orderBy('created_at', 'desc')
                ->get();
        });
    }

    public function searchVehicles(array $params): LengthAwarePaginator
    {
        $cacheKey = 'vehicles:search:' . md5(serialize($params));

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
        if (strlen($search) > 2) {
            return $query->where(function ($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('license_plate', 'like', "%{$search}%")
                    ->orWhere('vin', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    private function applyFilters(Builder $query, array $params): Builder
    {
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['make'])) {
            $query->where('make', $params['make']);
        }

        if (isset($params['model'])) {
            $query->where('model', $params['model']);
        }

        if (isset($params['year'])) {
            $query->where('year', $params['year']);
        }

        if (isset($params['customer_id'])) {
            $query->where('customer_id', $params['customer_id']);
        }

        if (isset($params['created_from'])) {
            $query->where('created_at', '>=', $params['created_from']);
        }

        if (isset($params['created_to'])) {
            $query->where('created_at', '<=', $params['created_to']);
        }

        return $query;
    }

    private function applySorting(Builder $query, array $params): Builder
    {
        $sortField = $params['sort_by'] ?? 'created_at';
        $sortDirection = $params['sort_direction'] ?? 'desc';

        return $query->orderBy($sortField, $sortDirection);
    }

    private function invalidateCache(): void
    {
        $keys = [
            'vehicles:active',
            'vehicles:recent',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Clear customer-specific caches
        $customerIds = $this->model->select('customer_id')->distinct()->pluck('customer_id');
        foreach ($customerIds as $customerId) {
            Cache::forget("vehicles:customer:{$customerId}");
        }
    }
}
