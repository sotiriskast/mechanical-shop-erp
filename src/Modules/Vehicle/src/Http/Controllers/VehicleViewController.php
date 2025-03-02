<?php

namespace Modules\Vehicle\src\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Vehicle\src\Http\Requests\V1\CreateVehicleRequest;
use Modules\Vehicle\src\Http\Requests\V1\UpdateVehicleRequest;
use Modules\Vehicle\src\Http\Resources\V1\VehicleResource;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Services\VehicleSearchService;
use Modules\Vehicle\src\Services\VehicleService;
use Modules\Customer\src\Models\Customer;
use Modules\Customer\src\Http\Resources\V1\CustomerResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VehicleViewController extends Controller
{
    public function __construct(
        private readonly VehicleService $vehicleService,
        private readonly VehicleSearchService $searchService,
    ) {
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
            'per_page' => 10
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
        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();

        return Inertia::render('Vehicle/Create', [
            'customers' => CustomerResource::collection($customers),
        ]);
    }

    public function store(CreateVehicleRequest $request)
    {
        try {
            $vehicle = $this->vehicleService->create($request->validated());

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
            $vehicle->load(['customer', 'serviceHistory', 'media']);

            return Inertia::render('Vehicle/Show', [
                'vehicle' => new VehicleResource($vehicle),
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
        $vehicle->load(['customer', 'media']);
        $customers = Customer::where('is_active', true)->orderBy('first_name')->get();

        return Inertia::render('Vehicle/Edit', [
            'vehicle' => new VehicleResource($vehicle),
            'customers' => CustomerResource::collection($customers),
            'can' => [
                'delete' => auth()->user()->can('delete-vehicles'),
            ]
        ]);
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        try {
            $this->vehicleService->update($vehicle->id, $request->validated());

            return redirect()->route('vehicles.index')
                ->with('flash', [
                    'type' => 'success',
                    'message' => 'Vehicle updated successfully!'
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
                ->with('success', 'Vehicle deleted successfully.');
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
}
