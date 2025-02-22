<?php
namespace App\Modules\Shared\Interfaces;

interface ServiceInterface
{
    public function list(array $params = []);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
