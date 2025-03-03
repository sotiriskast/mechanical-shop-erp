<?php

namespace Modules\Vehicle\src\Http\Controllers\V1;

use Illuminate\Http\Request;
use Modules\Core\src\Http\Controllers\BaseApiController;
use Modules\Vehicle\src\Http\Requests\V1\CreateVehicleRequest;
use Modules\Vehicle\src\Http\Requests\V1\UpdateVehicleRequest;
use Modules\Vehicle\src\Http\Resources\V1\VehicleResource;
use Modules\Vehicle\src\Services\VehicleSearchService;
use Modules\Vehicle\src\Services\VehicleService;

class VehicleController extends BaseApiController
{
    public function __construct(
        private readonly VehicleService       $vehicleService,
        private readonly VehicleSearchService $searchService
    )
    {
        $this->middleware('permission:view-vehicles')->only(['index', 'show', 'search']);
        $this->middleware('permission:create-vehicles')->only('store');
        $this->middleware('permission:edit-vehicles')->only('update');
        $this->middleware('permission:delete-vehicles')->only('destroy');
    }

    public function index(Request $request)
    {
        try {
            $vehicles = $this->vehicleService->list($request->all());
            return $this->successResponse(
                VehicleResource::collection($vehicles),
                'Vehicles retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $vehicles = $this->searchService->search($request->all());
            return $this->successResponse(
                VehicleResource::collection($vehicles),
                'Search results retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(CreateVehicleRequest $request)
    {
        try {
            $data = $request->toDTO();
            $vehicle = $this->vehicleService->create($data);
            return $this->createdResponse(
                new VehicleResource($vehicle),
                'Vehicle created successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $vehicle = $this->vehicleService->find($id);
            return $this->successResponse(
                new VehicleResource($vehicle),
                'Vehicle retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(UpdateVehicleRequest $request, int $id)
    {
        try {
            $data = $request->toDTO();
            $vehicle = $this->vehicleService->update($id, $data);
            return $this->successResponse(
                new VehicleResource($vehicle),
                'Vehicle updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->vehicleService->delete($id);
            return $this->noContentResponse('Vehicle deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getByCustomer(int $customerId)
    {
        try {
            $vehicles = $this->vehicleService->getVehiclesByCustomer($customerId);
            return $this->successResponse(
                VehicleResource::collection($vehicles),
                'Vehicles retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
