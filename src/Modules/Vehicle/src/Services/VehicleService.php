<?php

namespace Modules\Vehicle\src\Services;

use Modules\Core\src\Abstracts\BaseService;
use Modules\Vehicle\src\Actions\CreateVehicleAction;
use Modules\Vehicle\src\Actions\UpdateVehicleAction;
use Modules\Vehicle\src\Actions\DeleteVehicleAction;
use Modules\Vehicle\src\DTOs\VehicleData;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Exceptions\VehicleException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Vehicle\src\Repositories\VehicleRepository;

class VehicleService extends BaseService
{
    public function __construct(
        VehicleRepository $repository,
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

    public function create(array $data): Vehicle
    {
        try {
            $vehicleData = VehicleData::fromRequest($data);
            return $this->createAction->execute($vehicleData);
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.create_failed'),
                0,
                $e
            );
        }
    }

    public function update(int $id, array $data): Vehicle
    {
        try {
            $vehicleData = VehicleData::fromRequest($data);
            return $this->updateAction->execute($id, $vehicleData);
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

    public function getCustomerVehicles(int $customerId): Collection
    {
        try {
            return $this->repository->getCustomerVehicles($customerId);
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.fetch_failed'),
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

    public function getActiveVehicles(): Collection
    {
        try {
            return $this->repository->getActiveVehicles();
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.fetch_failed'),
                0,
                $e
            );
        }
    }
}
