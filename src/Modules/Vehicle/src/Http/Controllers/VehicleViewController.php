<?php

namespace Modules\Vehicle\src\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Modules\Vehicle\src\Http\Requests\V1\CreateVehicleRequest;
use Modules\Vehicle\src\Http\Requests\V1\UpdateVehicleRequest;
use Modules\Vehicle\src\Http\Resources\V1\VehicleResource;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Services\VehicleSearchService;
use Modules\Vehicle\src\Services\VehicleService;
use Modules\Customer\src\Services\CustomerService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VehicleViewController extends Controller
{
    public function __construct(
        private readonly VehicleService       $vehicleService,
        private readonly VehicleSearchService $searchService,
        private readonly CustomerService      $customerService
    )
    {
        $this->middleware('permission:view-vehicles')->only(['index', 'show']);
        $this->middleware('permission:create-vehicles')->only(['create', 'store']);
        $this->middleware('permission:edit-vehicles')->only(['edit', 'update']);
        $this->middleware('permission:delete-vehicles')->only('destroy');
    }

    public function index(Request $request)
    {
        $vehicles = $this->searchService->search([
            'q' => $request->input('search'),
            'sort' => $request->input('sort'),
            'direction' => $request->input('direction', 'asc'),
            'customer_id' => $request->input('customer_id'),
            'per_page' => 10,
            'include' => ['customer']
        ]);

        return Inertia::render('Vehicle/Index', [
            'vehicles' => VehicleResource::collection($vehicles),
            'filters' => $request->only(['search', 'sort', 'direction', 'customer_id']),
            'can' => [
                'create' => auth()->user()->can('create-vehicles'),
                'edit' => auth()->user()->can('edit-vehicles'),
                'delete' => auth()->user()->can('delete-vehicles'),
            ]
        ]);
    }

    public function create()
    {
        // Get active customers for the dropdown
        $customers = $this->customerService->getActiveCustomers();

        return Inertia::render('Vehicle/Create', [
            'customers' => $customers
        ]);
    }

    public function store(CreateVehicleRequest $request)
    {
        try {
            $this->vehicleService->create($request->toDTO());

            return redirect()->route('vehicles.index')
                ->with('flash', [
                    'type' => 'success',
                    'message' => trans('vehicles.messages.created')
                ]);
        } catch (\Exception $e) {
            Log::error('Failed to create vehicle', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Vehicle $vehicle)
    {
        try {
            $vehicle->load(['customer', 'serviceHistory']);

            return Inertia::render('Vehicle/Show', [
                'vehicle' => new VehicleResource($vehicle),
                'serviceHistory' => $vehicle->serviceHistory,
                'can' => [
                    'edit' => auth()->user()->can('edit-vehicles'),
                    'delete' => auth()->user()->can('delete-vehicles'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing vehicle', [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('vehicles.index')
                ->with('flash', [
                    'type' => 'error',
                    'message' => 'Unable to load vehicle details.'
                ]);
        }
    }

    public function edit(Vehicle $vehicle)
    {
        $vehicle->load('media');
        $customers = $this->customerService->getActiveCustomers();

        return Inertia::render('Vehicle/Edit', [
            'vehicle' => new VehicleResource($vehicle),
            'customers' => $customers,
            'can' => [
                'delete' => auth()->user()->can('delete-vehicles'),
            ]
        ]);
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        try {
            $this->vehicleService->update($vehicle->id, $request->toDTO());

            return redirect()->route('vehicles.index')
                ->with('flash', [
                    'type' => 'success',
                    'message' => trans('vehicles.messages.updated')
                ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Vehicle $vehicle)
    {
        try {
            $this->vehicleService->delete($vehicle->id);

            return redirect()->route('vehicles.index')
                ->with('flash', [
                    'type' => 'success',
                    'message' => trans('vehicles.messages.deleted')
                ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // IMPORTANT: This is likely the problematic method
    // Modified to handle both Inertia and AJAX requests
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
