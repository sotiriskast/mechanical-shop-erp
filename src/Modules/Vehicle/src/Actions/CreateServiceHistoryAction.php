<?php

namespace Modules\Vehicle\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Vehicle\src\DTOs\ServiceHistoryData;
use Modules\Vehicle\src\Events\ServiceHistoryCreated;
use Modules\Vehicle\src\Models\ServiceHistory;
use Modules\Vehicle\src\Repositories\ServiceHistoryRepository;
use Modules\Vehicle\src\Services\VehicleMediaService;
use Modules\Vehicle\src\Exceptions\ServiceHistoryException;

class CreateServiceHistoryAction
{
    public function __construct(
        private ServiceHistoryRepository $repository,
        private VehicleMediaService $mediaService
    ) {}

    public function execute(ServiceHistoryData $data): ServiceHistory
    {
        try {
            return DB::transaction(function () use ($data) {
                $serviceHistory = $this->repository->create($data->toArray());

                if (!empty($data->documents)) {
                    $this->mediaService->attachDocuments($serviceHistory, $data->documents, 'service_documents');
                }

                event(new ServiceHistoryCreated($serviceHistory));

                return $serviceHistory;
            });
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.create_service_history_failed'),
                0,
                $e
            );
        }
    }
}
