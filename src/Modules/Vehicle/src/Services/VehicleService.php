<?php

namespace Modules\Vehicle\src\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\src\Abstracts\BaseService;
use Modules\Vehicle\src\Actions\CreateVehicleAction;
use Modules\Vehicle\src\Actions\DeleteVehicleAction;
use Modules\Vehicle\src\Actions\UpdateVehicleAction;
use Modules\Vehicle\src\DTOs\VehicleData;
use Modules\Vehicle\src\Exceptions\VehicleException;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Repositories\VehicleRepository;

class VehicleService extends BaseService
{
    public function __construct(
        VehicleRepository                    $repository,
        private readonly CreateVehicleAction $createAction,
        private readonly UpdateVehicleAction $updateAction,
        private readonly DeleteVehicleAction $deleteAction
    ) {
        parent::__construct($repository);
    }

    public function list(array $params = []): LengthAwarePaginator
    {
        try {
            return $this->repository->searchVehicles($params);
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.fetch_failed'),
                0,
                $e
            );
        }
    }

    public function create(VehicleData|array $data): Vehicle
    {
        try {
            return $this->createAction->execute($data);
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.create_failed'),
                0,
                $e
            );
        }
    }

    public function update(int $id, VehicleData $data): Vehicle
    {
        try {
            return $this->updateAction->execute($id, $data);
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.update_failed'),
                0,
                $e
            );
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->deleteAction->execute($id);
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.delete_failed'),
                0,
                $e
            );
        }
    }

    public function findByLicensePlate(string $licensePlate): ?Vehicle
    {
        try {
            return $this->repository->findByLicensePlate($licensePlate);
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.fetch_failed'),
                0,
                $e
            );
        }
    }

    public function findByVIN(string $vin): ?Vehicle
    {
        try {
            return $this->repository->findByVIN($vin);
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.fetch_failed'),
                0,
                $e
            );
        }
    }

    public function getVehiclesByCustomer(int $customerId): array
    {
        try {
            return $this->repository->getVehiclesByCustomer($customerId)->toArray();
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.fetch_failed'),
                0,
                $e
            );
        }
    }
}
