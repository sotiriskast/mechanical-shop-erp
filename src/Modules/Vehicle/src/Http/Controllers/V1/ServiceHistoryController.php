<?php

namespace Modules\Vehicle\src\Http\Controllers\V1;

use Illuminate\Http\Request;
use Modules\Core\src\Http\Controllers\BaseApiController;
use Modules\Vehicle\src\Http\Requests\V1\CreateServiceHistoryRequest;
use Modules\Vehicle\src\Http\Requests\V1\UpdateServiceHistoryRequest;
use Modules\Vehicle\src\Http\Resources\V1\ServiceHistoryResource;
use Modules\Vehicle\src\Services\ServiceHistoryService;

class ServiceHistoryController extends BaseApiController
{
    public function __construct(
        private readonly ServiceHistoryService $serviceHistoryService
    )
    {
        $this->middleware('permission:view-vehicles')->only(['index', 'show', 'getByVehicle']);
        $this->middleware('permission:create-vehicles')->only('store');
        $this->middleware('permission:edit-vehicles')->only('update');
        $this->middleware('permission:delete-vehicles')->only('destroy');
    }

    public function index(Request $request)
    {
        try {
            $serviceHistories = $this->serviceHistoryService->list($request->all());
            return $this->successResponse(
                ServiceHistoryResource::collection($serviceHistories),
                'Service histories retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(CreateServiceHistoryRequest $request)
    {
        try {
            $data = $request->toDTO();
            $serviceHistory = $this->serviceHistoryService->create($data);
            return $this->createdResponse(
                new ServiceHistoryResource($serviceHistory),
                'Service history created successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $serviceHistory = $this->serviceHistoryService->find($id);
            return $this->successResponse(
                new ServiceHistoryResource($serviceHistory),
                'Service history retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(UpdateServiceHistoryRequest $request, int $id)
    {
        try {
            $data = $request->toDTO();
            $serviceHistory = $this->serviceHistoryService->update($id, $data);
            return $this->successResponse(
                new ServiceHistoryResource($serviceHistory),
                'Service history updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->serviceHistoryService->delete($id);
            return $this->noContentResponse('Service history deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getByVehicle(int $vehicleId)
    {
        try {
            $serviceHistories = $this->serviceHistoryService->getServiceHistoryByVehicle($vehicleId);
            return $this->successResponse(
                ServiceHistoryResource::collection($serviceHistories),
                'Service histories retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
