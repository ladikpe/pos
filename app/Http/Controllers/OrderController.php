<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexOrderInventoryRequest;
use App\Http\Requests\IndexOrderRequest;
use App\Http\Requests\IndexOrderUserRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseTraits;
    protected $orderServices;
    public function __construct(OrderServices $orderServices)
    {
        $this->orderServices = $orderServices;
    }

    public function index(IndexOrderRequest  $indexOrderRequest): JsonResponse
    {
        $response = $this->orderServices->index($indexOrderRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    /**
     * @throws \Exception
     */
    public function store(StoreOrderRequest $storeOrderRequest)
    {
        $response = $this->orderServices->store($storeOrderRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function show($id): JsonResponse
    {
        $response = $this->orderServices->show($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function fetchAllInventory(IndexOrderInventoryRequest  $indexOrderInventoryRequest): JsonResponse
    {
        $response = $this->orderServices->fetchAllInventory($indexOrderInventoryRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function fetchAllCustomer(Request  $request): JsonResponse
    {
        $response = $this->orderServices->fetchAllCustomer($request->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function fetchAllOrders(Request $request): JsonResponse
    {
        $response = $this->orderServices->fetchAllOrders($request->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }


    public  function getCashierByOrderId(IndexOrderUserRequest $indexOrderUserRequest): JsonResponse
    {
        $response = $this->orderServices->getCashierByOrderId($indexOrderUserRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function deleteOrder($id): JsonResponse
    {
        $response = $this->orderServices->deleteOrder($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function getOrdersWithTrash() :JsonResponse
    {
        $response = $this->orderServices->getOrdersWithTrash();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function showTrashed($id) : JsonResponse
    {
        $response = $this->orderServices->showTrashed($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

}
