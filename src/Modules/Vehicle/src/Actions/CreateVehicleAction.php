<?php

namespace Modules\Vehicle\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Vehicle\src\DTOs\VehicleData;
use Modules\Vehicle\src\Events\VehicleCreated;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Repositories\VehicleRepository;
use Modules\Vehicle\src\Services\VehicleMediaService;
use Modules\Vehicle\src\Exceptions\VehicleException;

class CreateVehicleAction
{
    public function __construct(
        private VehicleRepository $repository,
        private VehicleMediaService $mediaService
    ) {}

    public function execute(VehicleData $data): Vehicle
    {
        try {
            return DB::transaction(function () use ($data) {
                $vehicle = $this->repository->create($data->toArray());

                if (!empty($data->documents)) {
                    $this->mediaService->attachDocuments($vehicle, $data->documents);
                }

                event(new VehicleCreated($vehicle));

                return $vehicle;
            });
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.create_failed'),
                0,
                $e
            );
        }
    }
}
