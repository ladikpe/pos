<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditInventoryRequest;
use App\Http\Requests\IndexInventoryRequest;
use App\Http\Requests\InventoryQuantityReStockRequest;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryQuantityReStockRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\InventoryServices;

class InventoryController extends Controller
{
    use ResponseTraits;
    protected $inventoryServices;

    public function __construct(InventoryServices $inventoryServices)
    {
        $this->inventoryServices = $inventoryServices;
    }

    public function index(IndexInventoryRequest $indexInventoryRequest): JsonResponse
    {
        $response = $this->inventoryServices->index($indexInventoryRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function store(StoreInventoryRequest $storeInventoryRequest): JsonResponse
    {
        $response = $this->inventoryServices->storeInventories($storeInventoryRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function edit(EditInventoryRequest $request): JsonResponse
    {
        $response = $this->inventoryServices->editInventories($request->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function update(UpdateInventoryRequest $updateInventoryRequest): JsonResponse
    {
        $response = $this->inventoryServices->updateInventory($updateInventoryRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function delete($id): JsonResponse
    {
        $response = $this->inventoryServices->destroy($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function inventoryQuantityRestock(InventoryQuantityReStockRequest $inventoryQuantityReStockRequest): JsonResponse
    {
        $response = $this->inventoryServices->inventoryQuantityRestock($inventoryQuantityReStockRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '');
    }
}
