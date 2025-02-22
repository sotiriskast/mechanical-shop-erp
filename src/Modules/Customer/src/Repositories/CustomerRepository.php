<?php

namespace Modules\Customer\src\Repositories;

use Modules\Core\src\Abstracts\BaseRepository;
use Modules\Customer\src\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function searchCustomers(array $params)
    {
        $query = $this->model->query();

        if (isset($params['search'])) {
            $search = $params['search'];

            // Use full-text search for better performance
            if (strlen($search) > 2) {
                $query->whereRaw("MATCH(first_name, last_name, email, company_name) AGAINST(? IN BOOLEAN MODE)", [$search . '*']);
            } else {
                // Fall back to LIKE for short search terms
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            }
        }

        // Filter by status
        if (isset($params['is_active'])) {
            $query->where('is_active', $params['is_active']);
        }

        // Filter by VAT number
        if (isset($params['vat_number'])) {
            $query->where('vat_number', $params['vat_number']);
        }

        // Filter by date range
        if (isset($params['created_from'])) {
            $query->where('created_at', '>=', $params['created_from']);
        }

        if (isset($params['created_to'])) {
            $query->where('created_at', '<=', $params['created_to']);
        }

        // Add sorting
        $sortField = $params['sort_by'] ?? 'created_at';
        $sortDirection = $params['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Eager load relationships if needed
        if (isset($params['with'])) {
            $query->with($params['with']);
        }

        // Return paginated results
        return $query->paginate($params['per_page'] ?? 15);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function findByVatNumber(string $vatNumber)
    {
        return $this->model->where('vat_number', $vatNumber)->first();
    }

    public function getActiveCustomers()
    {
        return $this->model->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getCustomersByCity(string $city)
    {
        return $this->model->where('city', $city)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRecentCustomers(int $limit = 5)
    {
        return $this->model->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
