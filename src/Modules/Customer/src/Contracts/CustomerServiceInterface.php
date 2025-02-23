<?php

namespace Modules\Customer\src\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Customer\src\Models\Customer;

interface CustomerServiceInterface
{
    public function list(array $params = []): LengthAwarePaginator;
    public function create(array $data): Customer;
    public function update(int $id, array $data): Customer;
    public function delete(int $id): bool;
    public function findByCode(string $code): ?Customer;
    public function getActiveCustomers(): Collection;
}
