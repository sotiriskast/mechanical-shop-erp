<?php

namespace Modules\Customer\src\Http\Controllers;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Customer\src\Http\Requests\V1\CreateCustomerRequest;
use Modules\Customer\src\Http\Requests\V1\UpdateCustomerRequest;
use Modules\Customer\src\Http\Resources\V1\CustomerResource;
use Modules\Customer\src\Models\Customer;
use Modules\Customer\src\Services\CustomerService;

class CustomerViewController extends Controller
{
    public function __construct(
        protected CustomerService $customerService
    ) {
        $this->middleware('permission:view-customers')->only(['index', 'show']);
        $this->middleware('permission:create-customers')->only(['create', 'store']);
        $this->middleware('permission:edit-customers')->only(['edit', 'update']);
        $this->middleware('permission:delete-customers')->only('destroy');
    }

    public function index(Request $request)
    {
        $customers = Customer::query()
            ->when($request->input('search'), function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->input('sort'), function($query, $sort) use ($request) {
                $direction = $request->input('direction', 'asc');
                $query->orderBy($sort, $direction);
            }, function($query) {
                $query->latest();
            })
            ->paginate(10)
            ->withQueryString();

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

    public function create()
    {
        return Inertia::render('Customer/Create');
    }

    public function store(CreateCustomerRequest $request)
    {
        try {
            $customer = $this->customerService->create($request->validated());

            return redirect()->route('customers.index')
                ->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Customer $customer)
    {
        return Inertia::render('Customer/Show', [
            'customer' => new CustomerResource($customer),
            'can' => [
                'edit' => auth()->user()->can('edit-customers'),
                'delete' => auth()->user()->can('delete-customers'),
            ]
        ]);
    }

    public function edit(Customer $customer)
    {
        return Inertia::render('Customer/Edit', [
            'customer' => new CustomerResource($customer)
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            $this->customerService->update($customer->id, $request->validated());

            return redirect()->route('customers.index')
                ->with('success', 'Customer updated successfully.');
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
}
