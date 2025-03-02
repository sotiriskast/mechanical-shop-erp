<?php

namespace Modules\Vehicle\src\Services;

use Modules\Core\src\Abstracts\BaseService;
use Modules\Vehicle\src\Actions\CreateServiceHistoryAction;
use Modules\Vehicle\src\Actions\UpdateServiceHistoryAction;
use Modules\Vehicle\src\Actions\DeleteServiceHistoryAction;
use Modules\Vehicle\src\DTOs\ServiceHistoryData;
use Modules\Vehicle\src\Models\ServiceHistory;
use Modules\Vehicle\src\Exceptions\ServiceHistoryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Vehicle\src\Repositories\ServiceHistoryRepository;

class ServiceHistoryService extends BaseService
{
    public function __construct(
        ServiceHistoryRepository $repository,
        private readonly CreateServiceHistoryAction $createAction,
        private readonly UpdateServiceHistoryAction $updateAction,
        private readonly DeleteServiceHistoryAction $deleteAction
    ) {
        parent::__construct($repository);
    }

    public function list(array $params = []): LengthAwarePaginator
    {
        try {
            return $this->repository->searchServiceHistory($params);
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.fetch_service_history_failed'),
                0,
                $e
            );
        }
    }

    public function create(array $data): ServiceHistory
    {
        try {
            $serviceHistoryData = ServiceHistoryData::fromRequest($data);
            return $this->createAction->execute($serviceHistoryData);
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.create_service_history_failed'),
                0,
                $e
            );
        }
    }

    public function update(int $id, array $data): ServiceHistory
    {
        try {
            $serviceHistoryData = ServiceHistoryData::fromRequest($data);
            return $this->updateAction->execute($id, $serviceHistoryData);
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.update_service_history_failed'),
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
            throw new ServiceHistoryException(
                trans('vehicles.errors.delete_service_history_failed'),
                0,
                $e
            );
        }
    }

    public function getVehicleServiceHistory(int $vehicleId): Collection
    {
        try {
            return $this->repository->getVehicleServiceHistory($vehicleId);
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.fetch_service_history_failed'),
                0,
                $e
            );
        }
    }
}
