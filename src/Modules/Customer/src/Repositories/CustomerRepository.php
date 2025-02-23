<?php

namespace Modules\Customer\src\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Core\src\Abstracts\BaseRepository;
use Modules\Customer\src\Contracts\CustomerRepositoryInterface;
use Modules\Customer\src\Models\Customer;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function find(int $id): ?Customer
    {
        return $this->model->find($id);
    }

    public function create(array $data): Customer
    {
        $customer = parent::create($data);
        $this->invalidateCache();
        return $customer;
    }

    public function update(int $id, array $data): Customer
    {
        $customer = parent::update($id, $data);
        $this->invalidateCache();
        return $customer;
    }

    public function delete(int $id): bool
    {
        $result = parent::delete($id);
        $this->invalidateCache();
        return $result;
    }

    public function findByEmail(string $email): ?Customer
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByCode(string $code): ?Customer
    {
        return $this->model->where('code', $code)->first();
    }

    public function findByVatNumber(string $vatNumber): ?Customer
    {
        return $this->model->where('vat_number', $vatNumber)->first();
    }

    public function getActiveCustomers(): Collection
    {
        return Cache::remember('customers:active', self::CACHE_TTL, function () {
            return $this->model->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();
        });
    }

    public function searchCustomers(array $params): LengthAwarePaginator
    {
        $cacheKey = 'customers:search:' . md5(serialize($params));

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
            return $query->whereRaw(
                "MATCH(first_name, last_name, email, company_name) AGAINST(? IN BOOLEAN MODE)",
                [$search . '*']
            );
        }

        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        });
    }

    private function applyFilters(Builder $query, array $params): Builder
    {
        if (isset($params['is_active'])) {
            $query->where('is_active', $params['is_active']);
        }

        if (isset($params['vat_number'])) {
            $query->where('vat_number', $params['vat_number']);
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
            'customers:active',
            'customers:recent',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
