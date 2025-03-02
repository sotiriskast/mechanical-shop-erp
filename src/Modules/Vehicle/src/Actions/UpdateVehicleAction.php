<?php

namespace Modules\Vehicle\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Vehicle\src\DTOs\VehicleData;
use Modules\Vehicle\src\Events\VehicleUpdated;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Repositories\VehicleRepository;
use Modules\Vehicle\src\Services\VehicleMediaService;
use Modules\Vehicle\src\Exceptions\VehicleException;

class UpdateVehicleAction
{
    public function __construct(
        private VehicleRepository $repository,
        private VehicleMediaService $mediaService
    ) {}

    public function execute(int $id, VehicleData $data): Vehicle
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $vehicle = $this->repository->find($id);

                if (!$vehicle) {
                    throw new VehicleException(trans('vehicles.errors.not_found'));
                }

                $vehicle = $this->repository->update($id, $data->toArray());

                if (!empty($data->documents)) {
                    $this->mediaService->attachDocuments($vehicle, $data->documents);
                }

                event(new VehicleUpdated($vehicle));

                return $vehicle;
            });
        } catch (VehicleException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.update_failed'),
                0,
                $e
            );
        }
    }
}
