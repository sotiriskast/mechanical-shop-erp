<?php

namespace Modules\Customer\src\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Customer\src\Http\Requests\V1\CreateCustomerRequest;
use Modules\Customer\src\Http\Requests\V1\UpdateCustomerRequest;
use Modules\Customer\src\Http\Resources\V1\CustomerResource;
use Modules\Customer\src\Models\Customer;
use Modules\Customer\src\Services\CustomerSearchService;
use Modules\Customer\src\Services\CustomerService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomerViewController extends Controller
{
    public function __construct(
        private readonly CustomerService $customerService,
        private readonly CustomerSearchService $searchService,
    ) {
        $this->middleware('permission:view-customers')->only(['index', 'show']);
        $this->middleware('permission:create-customers')->only(['create', 'store']);
        $this->middleware('permission:edit-customers')->only(['edit', 'update']);
        $this->middleware('permission:delete-customers')->only('destroy');
    }

    public function index(Request $request)
    {
        $customers = $this->searchService->search([
            'q' => $request->input('search'),
            'sort' => $request->input('sort'),
            'direction' => $request->input('direction', 'asc'),
            'per_page' => 10
        ]);

        return Inertia::render('Customer/Index', [
            'customers' => CustomerResource::collection($customers),
            'filters' => $request->only(['search', 'sort', 'direction']),
            'can' => [
                'create' => auth()->user()->can('create-customers'),
                'edit' => auth()->user()->can('edit-customers'),
                'delete' => auth()->user()->can('delete-customers'),
            ]
        ]);
    }

    public function store(CreateCustomerRequest $request)
    {
        try {
            $customer = $this->customerService->create($request->validated());

            return redirect()->route('customers.index')
                ->with('flash', [
                    'type' => 'success',
                    'message' => trans('customers.messages.created')
                ]);
        } catch (\Exception $e) {
            Log::error('Failed to create customer', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function create()
    {
        return Inertia::render('Customer/Create');
    }


    public function show(Customer $customer)
    {
        try {
            $customer->load('media');

            return Inertia::render('Customer/Show', [
                'customer' => new CustomerResource($customer),
                'can' => [
                    'edit' => auth()->user()->can('edit-customers'),
                    'delete' => auth()->user()->can('delete-customers'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing customer', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('customers.index')
                ->with('flash', [
                    'type' => 'error',
                    'message' => 'Unable to load customer details.'
                ]);
        }
    }

    public function edit(Customer $customer)
    {
        $customer->load('media');

        Log::debug('Customer data:', ['customer' => $customer->toArray()]);

        return Inertia::render('Customer/Edit', [
            'customer' => new CustomerResource($customer),
            'can' => [
                'delete' => auth()->user()->can('delete-customers'),
            ]
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            $this->customerService->update($customer->id, $request->validated());

            return redirect()->route('customers.index')
                ->with('flash', [
                    'type' => 'success',
                    'message' => 'Customer updated successfully!'
                ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $this->customerService->delete($customer->id);

            return redirect()->route('customers.index')
                ->with('success', 'Customer deleted successfully.');
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
