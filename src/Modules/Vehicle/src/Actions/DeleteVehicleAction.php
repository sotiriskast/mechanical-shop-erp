<?php

namespace Modules\Vehicle\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Vehicle\src\Events\VehicleDeleted;
use Modules\Vehicle\src\Exceptions\VehicleException;
use Modules\Vehicle\src\Repositories\VehicleRepository;

class DeleteVehicleAction
{
    public function __construct(
        private VehicleRepository $repository
    ) {}

    public function execute(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $vehicle = $this->repository->find($id);

                if (!$vehicle) {
                    throw new VehicleException(trans('vehicles.errors.not_found'));
                }

                // Check if vehicle has related records that should prevent deletion
                if ($this->hasRelatedRecords($vehicle)) {
                    throw new VehicleException(trans('vehicles.errors.has_related_records'));
                }

                $deleted = $this->repository->delete($id);

                if ($deleted) {
                    event(new VehicleDeleted($vehicle));
                }

                return $deleted;
            });
        } catch (VehicleException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new VehicleException(
                trans('vehicles.errors.delete_failed'),
                0,
                $e
            );
        }
    }

    private function hasRelatedRecords($vehicle): bool
    {
        // If a vehicle has active work orders, it shouldn't be deleted
        // This will be expanded when Workshop module is implemented
        return false;
    }
}
