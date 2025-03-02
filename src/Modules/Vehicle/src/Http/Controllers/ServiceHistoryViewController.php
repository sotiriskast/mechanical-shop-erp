<?php

namespace Modules\Vehicle\src\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Vehicle\src\Http\Requests\V1\CreateServiceHistoryRequest;
use Modules\Vehicle\src\Http\Requests\V1\UpdateServiceHistoryRequest;
use Modules\Vehicle\src\Http\Resources\V1\ServiceHistoryResource;
use Modules\Vehicle\src\Http\Resources\V1\VehicleResource;
use Modules\Vehicle\src\Models\ServiceHistory;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Services\ServiceHistoryService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ServiceHistoryViewController extends Controller
{
    public function __construct(
        private readonly ServiceHistoryService $serviceHistoryService,
    ) {
        $this->middleware('permission:view-vehicles')->only(['index', 'show']);
        $this->middleware('permission:create-vehicles')->only(['create', 'store']);
        $this->middleware('permission:edit-vehicles')->only(['edit', 'update']);
        $this->middleware('permission:delete-vehicles')->only('destroy');
    }

    public function index(Request $request, Vehicle $vehicle)
    {
        $serviceHistory = $this->serviceHistoryService->list([
            'vehicle_id' => $vehicle->id,
            'sort' => $request->input('sort', 'service_date'),
            'direction' => $request->input('direction', 'desc'),
            'per_page' => 10
        ]);

        return Inertia::render('Vehicle/ServiceHistory/Index', [
            'vehicle' => new VehicleResource($vehicle),
            'serviceHistory' => ServiceHistoryResource::collection($serviceHistory),
            'filters' => $request->only(['sort', 'direction']),
            'can' => [
                'create' => auth()->user()->can('create-vehicles'),
                'edit' => auth()->user()->can('edit-vehicles'),
                'delete' => auth()->user()->can('delete-vehicles'),
            ]
        ]);
    }

    public function create(Vehicle $vehicle)
    {
        return Inertia::render('Vehicle/ServiceHistory/Create', [
            'vehicle' => new VehicleResource($vehicle),
            'serviceTypes' => $this->getServiceTypes(),
        ]);
    }

    public function store(CreateServiceHistoryRequest $request)
    {
        try {
            $serviceHistory = $this->serviceHistoryService->create($request->validated());
            $vehicleId = $serviceHistory->vehicle_id;

            return redirect()->route('vehicles.service-history.index', $vehicleId)
                ->with('flash', [
                    'type' => 'success',
                    'message' => trans('vehicles.messages.service_history_created')
                ]);
        } catch (\Exception $e) {
            Log::error('Failed to create service history', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Vehicle $vehicle, ServiceHistory $serviceHistory)
    {
        try {
            $serviceHistory->load(['vehicle', 'media']);

            return Inertia::render('Vehicle/ServiceHistory/Show', [
                'vehicle' => new VehicleResource($vehicle),
                'serviceHistory' => new ServiceHistoryResource($serviceHistory),
                'can' => [
                    'edit' => auth()->user()->can('edit-vehicles'),
                    'delete' => auth()->user()->can('delete-vehicles'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing service history', [
                'service_history_id' => $serviceHistory->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('vehicles.service-history.index', $vehicle->id)
                ->with('flash', [
                    'type' => 'error',
                    'message' => 'Unable to load service history details.'
                ]);
        }
    }

    public function edit(Vehicle $vehicle, ServiceHistory $serviceHistory)
    {
        $serviceHistory->load(['vehicle', 'media']);

        return Inertia::render('Vehicle/ServiceHistory/Edit', [
            'vehicle' => new VehicleResource($vehicle),
            'serviceHistory' => new ServiceHistoryResource($serviceHistory),
            'serviceTypes' => $this->getServiceTypes(),
            'can' => [
                'delete' => auth()->user()->can('delete-vehicles'),
            ]
        ]);
    }

    public function update(UpdateServiceHistoryRequest $request, Vehicle $vehicle, ServiceHistory $serviceHistory)
    {
        try {
            $this->serviceHistoryService->update($serviceHistory->id, $request->validated());

            return redirect()->route('vehicles.service-history.index', $vehicle->id)
                ->with('flash', [
                    'type' => 'success',
                    'message' => 'Service history updated successfully!'
                ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Vehicle $vehicle, ServiceHistory $serviceHistory)
    {
        try {
            $this->serviceHistoryService->delete($serviceHistory->id);

            return redirect()->route('vehicles.service-history.index', $vehicle->id)
                ->with('success', 'Service history deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroyFile($fileId)
    {
        try {
            $media = Media::findOrFail($fileId);
            $media->delete();

            return response()->json(['message' => 'File deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getServiceTypes(): array
    {
        return [
            'Oil Change',
            'Tire Rotation',
            'Brake Service',
            'Engine Tune-Up',
            'Air Filter Replacement',
            'Battery Replacement',
            'Coolant Flush',
            'Transmission Service',
            'Wheel Alignment',
            'Emission Test',
            'General Maintenance',
            'Other',
        ];
    }
}
