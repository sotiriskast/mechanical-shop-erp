<?php

namespace Modules\Vehicle\src\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\src\Abstracts\BaseService;
use Modules\Vehicle\src\Actions\CreateServiceHistoryAction;
use Modules\Vehicle\src\Actions\DeleteServiceHistoryAction;
use Modules\Vehicle\src\Actions\UpdateServiceHistoryAction;
use Modules\Vehicle\src\DTOs\ServiceHistoryData;
use Modules\Vehicle\src\Exceptions\ServiceHistoryException;
use Modules\Vehicle\src\Models\ServiceHistory;
use Modules\Vehicle\src\Repositories\ServiceHistoryRepository;

class ServiceHistoryService extends BaseService
{
    public function __construct(
        ServiceHistoryRepository                    $repository,
        private readonly CreateServiceHistoryAction $createAction,
        private readonly UpdateServiceHistoryAction $updateAction,
        private readonly DeleteServiceHistoryAction $deleteAction
    )
    {
        parent::__construct($repository);
    }

    public function list(array $params = []): LengthAwarePaginator
    {
        try {
            return $this->repository->searchServiceHistory($params);
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.service_history.fetch_failed'),
                0,
                $e
            );
        }
    }

    public function create(array $data): ServiceHistory
    {
        // Convert array to DTO for internal use
        $serviceHistoryData = new ServiceHistoryData($data);

        // Use the DTO for your business logic
        $serviceHistory = ServiceHistory::create([
            'vehicle_id' => $serviceHistoryData->vehicleId,
            'service_date' => $serviceHistoryData->serviceDate,
            'description' => $serviceHistoryData->description,
            'cost' => $serviceHistoryData->cost,
            // Include any other fields from your DTO
        ]);

        // If you have any additional processing that needs the DTO, do it here

        return $serviceHistory;
    }

    public function update(int $id, ServiceHistoryData $data): ServiceHistory
    {
        try {
            return $this->updateAction->execute($id, $data);
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.service_history.update_failed'),
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
                trans('vehicles.errors.service_history.delete_failed'),
                0,
                $e
            );
        }
    }

    public function getServiceHistoryByVehicle(int $vehicleId): array
    {
        try {
            return $this->repository->getServiceHistoryByVehicle($vehicleId)->toArray();
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.service_history.fetch_failed'),
                0,
                $e
            );
        }
    }

    public function getRecentServiceHistories(int $limit = 10): array
    {
        try {
            return $this->repository->getRecentServiceHistories($limit)->toArray();
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.service_history.fetch_failed'),
                0,
                $e
            );
        }
    }
}
