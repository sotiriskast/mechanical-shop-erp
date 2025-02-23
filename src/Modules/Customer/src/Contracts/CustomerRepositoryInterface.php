<?php

namespace Modules\Customer\src\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Customer\src\Models\Customer;

interface CustomerRepositoryInterface
{
    public function find(int $id): ?Customer;
    public function create(array $data): Customer;
    public function update(int $id, array $data): Customer;
    public function delete(int $id): bool;
    public function searchCustomers(array $params): LengthAwarePaginator;
    public function findByEmail(string $email): ?Customer;
    public function findByCode(string $code): ?Customer;
    public function findByVatNumber(string $vatNumber): ?Customer;
    public function getActiveCustomers(): Collection;
}
