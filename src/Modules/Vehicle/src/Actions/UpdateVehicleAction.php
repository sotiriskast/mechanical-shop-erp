<?php

namespace Modules\Vehicle\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Vehicle\src\DTOs\VehicleData;
use Modules\Vehicle\src\Events\VehicleUpdated;
use Modules\Vehicle\src\Exceptions\VehicleException;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Repositories\VehicleRepository;
use Modules\Vehicle\src\Services\VehicleMediaService;

readonly class UpdateVehicleAction
{
    public function __construct(
        private VehicleRepository   $repository,
        private VehicleMediaService $mediaService
    )
    {
    }

    public function execute(int $id, VehicleData $data): Vehicle
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $vehicle = $this->repository->find($id);

                if (!$vehicle) {
                    throw new VehicleException(trans('vehicles.errors.not_found'));
                }

                // Check for duplicate license plate if changed
                if ($vehicle->license_plate !== $data->licensePlate) {
                    $existingVehicle = $this->repository->findByLicensePlate($data->licensePlate);
                    if ($existingVehicle && $existingVehicle->id !== $id) {
                        throw new VehicleException(trans('vehicles.errors.license_plate_exists'));
                    }
                }

                // Check for duplicate VIN if changed and provided
                if ($data->vin && $vehicle->vin !== $data->vin) {
                    $existingVehicle = $this->repository->findByVIN($data->vin);
                    if ($existingVehicle && $existingVehicle->id !== $id) {
                        throw new VehicleException(trans('vehicles.errors.vin_exists'));
                    }
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
