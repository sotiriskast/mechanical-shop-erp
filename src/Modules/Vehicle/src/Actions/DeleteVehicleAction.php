<?php

namespace Modules\Vehicle\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Vehicle\src\Events\VehicleDeleted;
use Modules\Vehicle\src\Exceptions\VehicleException;
use Modules\Vehicle\src\Repositories\VehicleRepository;

readonly class DeleteVehicleAction
{
    public function __construct(
        private VehicleRepository $repository,
    ) {}

    public function execute(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $vehicle = $this->repository->find($id);

                if (!$vehicle) {
                    throw new VehicleException(trans('vehicles.errors.not_found'));
                }

                // Check if there are any work orders associated with this vehicle (to be implemented when Workshop module is created)
                if ($this->hasRelatedWorkOrders($vehicle)) {
                    throw new VehicleException(trans('vehicles.errors.has_related_work_orders'));
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

    private function hasRelatedWorkOrders($vehicle): bool
    {
        // This will be implemented when Workshop module is created
        // For now, return false to allow deletion
        return false;
    }
}
