<?php

namespace Modules\Vehicle\src\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Vehicle\src\Events\ServiceHistoryDeleted;
use Modules\Vehicle\src\Exceptions\ServiceHistoryException;
use Modules\Vehicle\src\Repositories\ServiceHistoryRepository;

readonly class DeleteServiceHistoryAction
{
    public function __construct(
        private ServiceHistoryRepository $repository
    ) {}

    public function execute(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $serviceHistory = $this->repository->find($id);

                if (!$serviceHistory) {
                    throw new ServiceHistoryException(trans('vehicles.errors.service_history.not_found'));
                }

                $deleted = $this->repository->delete($id);

                if ($deleted) {
                    event(new ServiceHistoryDeleted($serviceHistory));
                }

                return $deleted;
            });
        } catch (ServiceHistoryException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServiceHistoryException(
                trans('vehicles.errors.service_history.delete_failed'),
                0,
                $e
            );
        }
    }
}
