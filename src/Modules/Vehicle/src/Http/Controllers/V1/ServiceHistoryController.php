<?php

namespace Modules\Vehicle\src\Http\Controllers\V1;

use Illuminate\Http\Request;
use Modules\Core\src\Http\Controllers\BaseApiController;
use Modules\Vehicle\src\Http\Requests\V1\StoreServiceHistoryRequest;
use Modules\Vehicle\src\Http\Requests\V1\UpdateServiceHistoryRequest;
use Modules\Vehicle\src\Http\Resources\V1\ServiceHistoryResource;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Models\ServiceHistoryService;
use Modules\Vehicle\src\Services\VehicleService;

class ServiceHistoryController extends BaseApiController
{
    protected $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
        $this->middleware('permission:view-vehicles')->only(['index', 'show']);
        $this->middleware('permission:create-vehicles')->only('store');
        $this->middleware('permission:edit-vehicles')->only('update');
        $this->middleware('permission:delete-vehicles')->only('destroy');
    }

    /**
     * Display a listing of service history records for a vehicle.
     */
    public function index(int $vehicleId, Request $request)
    {
        try {
            $vehicle = Vehicle::findOrFail($vehicleId);
            $filters = $request->all();
            $serviceHistory = $this->vehicleService->getServiceHistory($vehicle, $filters);

            return $this->successResponse(
                ServiceHistoryResource::collection($serviceHistory),
                'Service history retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store a new service history record.
     */
    public function store(int $vehicleId, StoreServiceHistoryRequest $request)
    {
        try {
            $vehicle = Vehicle::findOrFail($vehicleId);
            $serviceData = $request->validated();
            $serviceHistory = $this->vehicleService->addServiceHistory($vehicle, $serviceData);

            return $this->createdResponse(
                new ServiceHistoryResource($serviceHistory),
                'Service history created successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified service history record.
     */
    public function show(int $vehicleId, int $serviceId)
    {
        try {
            $serviceHistory = ServiceHistoryService::where('vehicle_id', $vehicleId)
                ->where('id', $serviceId)
                ->firstOrFail();

            return $this->successResponse(
                new ServiceHistoryResource($serviceHistory),
                'Service history retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified service history record.
     */
    public function update(int $vehicleId, int $serviceId, UpdateServiceHistoryRequest $request)
    {
        try {
            $serviceHistory = ServiceHistoryService::where('vehicle_id', $vehicleId)
                ->where('id', $serviceId)
                ->firstOrFail();

            $serviceHistory->update($request->validated());

            // Update vehicle mileage if needed
            if ($request->has('mileage')) {
                $vehicle = Vehicle::findOrFail($vehicleId);
                $vehicle->updateMileage($request->mileage);
            }

            return $this->successResponse(
                new ServiceHistoryResource($serviceHistory),
                'Service history updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified service history record.
     */
    public function destroy(int $vehicleId, int $serviceId)
    {
        try {
            $serviceHistory = ServiceHistoryService::where('vehicle_id', $vehicleId)
                ->where('id', $serviceId)
                ->firstOrFail();

            $serviceHistory->delete();

            return $this->noContentResponse('Service history deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Upload attachments to a service history record.
     */
    public function uploadAttachments(int $vehicleId, int $serviceId, Request $request)
    {
        try {
            $request->validate([
                'attachments' => 'required|array',
                'attachments.*' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            ]);

            $serviceHistory = ServiceHistoryService::where('vehicle_id', $vehicleId)
                ->where('id', $serviceId)
                ->firstOrFail();

            foreach ($request->file('attachments') as $file) {
                $serviceHistory->addMedia($file)
                    ->toMediaCollection('service_documents');
            }

            return $this->successResponse(
                new ServiceHistoryResource($serviceHistory->fresh()),
                'Attachments uploaded successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
