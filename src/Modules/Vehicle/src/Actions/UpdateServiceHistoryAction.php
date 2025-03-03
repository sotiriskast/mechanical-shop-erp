<?php

namespace Modules\Vehicle\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Vehicle\src\DTOs\ServiceHistoryData;
use Modules\Vehicle\src\Events\ServiceHistoryUpdated;
use Modules\Vehicle\src\Exceptions\ServiceHistoryException;
use Modules\Vehicle\src\Models\ServiceHistory;
use Modules\Vehicle\src\Repositories\ServiceHistoryRepository;
use Modules\Vehicle\src\Repositories\VehicleRepository;

readonly class UpdateServiceHistoryAction
{
    public function __construct(
        private ServiceHistoryRepository $repository,
        private VehicleRepository        $vehicleRepository
    )
    {
    }

    public function execute(int $id, ServiceHistoryData $data): ServiceHistory
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $serviceHistory = $this->repository->find($id);

                if (!$serviceHistory) {
                    throw new ServiceHistoryException(trans('vehicles.errors.service_history.not_found'));
                }

                // Verify vehicle exists
                $vehicle = $this->vehicleRepository->find($data->vehicleId);
                if (!$vehicle) {
                    throw new ServiceHistoryException(trans('vehicles.errors.vehicle_not_found'));
                }

                $serviceHistory = $this->repository->update($id, $data->toArray());

                if (!empty($data->documents)) {
                    $serviceHistory->addMultipleMediaFromRequest(['documents'])
                        ->each(function ($fileAdder) {
                            $fileAdder->toMediaCollection('documents');
                        });
                }

                event(new ServiceHistoryUpdated($serviceHistory));

                return $serviceHistory;
            });
        } catch (ServiceHistoryException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.service_history.update_failed'),
                0,
                $e
            );
        }
    }
}
