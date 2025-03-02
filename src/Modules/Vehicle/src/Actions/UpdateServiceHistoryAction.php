<?php

namespace Modules\Vehicle\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Vehicle\src\DTOs\ServiceHistoryData;
use Modules\Vehicle\src\Events\ServiceHistoryUpdated;
use Modules\Vehicle\src\Models\ServiceHistory;
use Modules\Vehicle\src\Repositories\ServiceHistoryRepository;
use Modules\Vehicle\src\Services\VehicleMediaService;
use Modules\Vehicle\src\Exceptions\ServiceHistoryException;

class UpdateServiceHistoryAction
{
    public function __construct(
        private ServiceHistoryRepository $repository,
        private VehicleMediaService $mediaService
    ) {}

    public function execute(int $id, ServiceHistoryData $data): ServiceHistory
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $serviceHistory = $this->repository->find($id);

                if (!$serviceHistory) {
                    throw new ServiceHistoryException(trans('vehicles.errors.service_history_not_found'));
                }

                $serviceHistory = $this->repository->update($id, $data->toArray());

                if (!empty($data->documents)) {
                    $this->mediaService->attachDocuments($serviceHistory, $data->documents, 'service_documents');
                }

                event(new ServiceHistoryUpdated($serviceHistory));

                return $serviceHistory;
            });
        } catch (ServiceHistoryException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.update_service_history_failed'),
                0,
                $e
            );
        }
    }
}
