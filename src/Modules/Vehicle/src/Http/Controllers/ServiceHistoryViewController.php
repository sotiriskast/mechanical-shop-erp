<?php

namespace Modules\Vehicle\src\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Modules\Vehicle\src\Http\Requests\V1\CreateServiceHistoryRequest;
use Modules\Vehicle\src\Http\Requests\V1\UpdateServiceHistoryRequest;
use Modules\Vehicle\src\Http\Resources\V1\ServiceHistoryResource;
use Modules\Vehicle\src\Http\Resources\V1\VehicleResource;
use Modules\Vehicle\src\Models\ServiceHistory;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Services\ServiceHistoryService;
use Modules\Vehicle\src\Services\VehicleService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ServiceHistoryViewController extends Controller
{
    public function __construct(
        private readonly ServiceHistoryService $serviceHistoryService,
        private readonly VehicleService $vehicleService
    ) {
        $this->middleware('permission:view-vehicles')->only(['index', 'show']);
        $this->middleware('permission:create-vehicles')->only(['create', 'store']);
        $this->middleware('permission:edit-vehicles')->only(['edit', 'update']);
        $this->middleware('permission:delete-vehicles')->only('destroy');
    }

    public function index(Request $request, Vehicle $vehicle)
    {
        $serviceHistories = $this->serviceHistoryService->list([
            'vehicle_id' => $vehicle->id,
            'sort' => $request->input('sort', 'service_date'),
            'direction' => $request->input('direction', 'desc'),
            'per_page' => 10
        ]);

        return Inertia::render('Vehicle/ServiceHistory/Index', [
            'vehicle' => $vehicle,
            'serviceHistories' => ServiceHistoryResource::collection($serviceHistories),
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
            'vehicle' => $vehicle
        ]);
    }

    public function store(CreateServiceHistoryRequest $request, Vehicle $vehicle)
    {
        try {
            $serviceHistory = $this->serviceHistoryService->create($request->toDTO());

            return redirect()->route('vehicles.service-history.index', $vehicle)
                ->with('flash', [
                    'type' => 'success',
                    'message' => trans('vehicles.messages.service_history.created')
                ]);
        } catch (\Exception $e) {
            Log::error('Failed to create service history', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

// Example fix for the show method in ServiceHistoryViewController
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
        $serviceHistory->load('media');

        return Inertia::render('Vehicle/ServiceHistory/Edit', [
            'vehicle' => $vehicle,
            'serviceHistory' => new ServiceHistoryResource($serviceHistory),
            'can' => [
                'delete' => auth()->user()->can('delete-vehicles'),
            ]
        ]);
    }

    public function update(UpdateServiceHistoryRequest $request, Vehicle $vehicle, ServiceHistory $serviceHistory)
    {
        try {
            $this->serviceHistoryService->update($serviceHistory->id, $request->toDTO());

            return redirect()->route('vehicles.service-history.index', $vehicle)
                ->with('flash', [
                    'type' => 'success',
                    'message' => trans('vehicles.messages.service_history.updated')
                ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Vehicle $vehicle, ServiceHistory $serviceHistory)
    {
        try {
            $this->serviceHistoryService->delete($serviceHistory->id);

            return redirect()->route('vehicles.service-history.index', $vehicle)
                ->with('flash', [
                    'type' => 'success',
                    'message' => trans('vehicles.messages.service_history.deleted')
                ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // IMPORTANT: Modified to handle both Inertia and AJAX requests
    public function destroyFile(Request $request, $fileId)
    {
        try {
            $media = Media::findOrFail($fileId);
            $media->delete();

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'File deleted successfully']);
            }

            // Otherwise, return a redirect for Inertia
            return redirect()->back()
                ->with('flash', [
                    'type' => 'success',
                    'message' => 'File deleted successfully'
                ]);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
