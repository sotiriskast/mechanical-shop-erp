<?php

namespace Modules\Vehicle\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Vehicle\src\DTOs\VehicleData;
use Modules\Vehicle\src\Events\VehicleCreated;
use Modules\Vehicle\src\Exceptions\VehicleException;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Repositories\VehicleRepository;
use Modules\Vehicle\src\Services\VehicleMediaService;

readonly class CreateVehicleAction
{
    public function __construct(
        private VehicleRepository   $repository,
        private VehicleMediaService $mediaService
    ) {}

    public function execute(VehicleData $data): Vehicle
    {
        try {
            return DB::transaction(function () use ($data) {
                // Check for duplicate license plate
                if ($this->repository->findByLicensePlate($data->licensePlate)) {
                    throw new VehicleException(trans('vehicles.errors.license_plate_exists'));
                }

                // Check for duplicate VIN if provided
                if ($data->vin && $this->repository->findByVIN($data->vin)) {
                    throw new VehicleException(trans('vehicles.errors.vin_exists'));
                }

                $vehicle = $this->repository->create($data->toArray());

                if (!empty($data->documents)) {
                    $this->mediaService->attachDocuments($vehicle, $data->documents);
                }

                event(new VehicleCreated($vehicle));

                return $vehicle;
            });
        } catch (VehicleException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.create_failed'),
                0,
                $e
            );
        }
    }
}
