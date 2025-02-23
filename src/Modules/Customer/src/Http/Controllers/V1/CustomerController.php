<?php
namespace Modules\Customer\src\Http\Controllers\V1;

use Illuminate\Http\Request;
use Modules\Core\src\Http\Controllers\BaseApiController;
use Modules\Customer\src\Contracts\CustomerServiceInterface;
use Modules\Customer\src\Exceptions\CustomerException;
use Modules\Customer\src\Http\Requests\V1\CreateCustomerRequest;
use Modules\Customer\src\Http\Requests\V1\UpdateCustomerRequest;
use Modules\Customer\src\Http\Resources\V1\CustomerResource;
use Modules\Customer\src\Services\CustomerSearchService;
use Modules\Customer\src\Services\CustomerService;

class CustomerController extends BaseApiController
{
    public function __construct(
        private readonly CustomerServiceInterface $customerService,
        private readonly CustomerSearchService $searchService
    ) {
        $this->middleware('permission:view-customers')->only(['index', 'show', 'search']);
        $this->middleware('permission:create-customers')->only('store');
        $this->middleware('permission:edit-customers')->only('update');
        $this->middleware('permission:delete-customers')->only('destroy');
    }

    public function search(Request $request)
    {
        try {
            $customers = $this->searchService->search($request->all());

            return $this->successResponse(
                CustomerResource::collection($customers),
                'Search results retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(CreateCustomerRequest $request)
    {
        try {
            $customer = $this->customerService->create($request->validated());
            return $this->createdResponse(
                new CustomerResource($customer),
                'Customer created successfully'
            );
        } catch (CustomerException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $customer = $this->customerService->find($id);
            return $this->successResponse(
                new CustomerResource($customer),
                'Customer retrieved successfully'
            );
        } catch (CustomerException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(UpdateCustomerRequest $request, int $id)
    {
        try {
            $customer = $this->customerService->update($id, $request->validated());
            return $this->successResponse(
                new CustomerResource($customer),
                'Customer updated successfully'
            );
        } catch (CustomerException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->customerService->delete($id);
            return $this->noContentResponse('Customer deleted successfully');
        } catch (CustomerException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

}
