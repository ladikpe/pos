<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerTypeRequest;
use App\Http\Requests\IndexCustomerRequest;
use App\Http\Requests\IndexPriceTypeRequest;
use App\Http\Requests\ShowBusinessSegmentRequest;
use App\Http\Requests\ShowCustomerRequest;
use App\Http\Requests\ShowCustomerTypeRequest;
use App\Http\Requests\StoreBusinessSegmentRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\StoreCustomerTypeRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Services\CustomerServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    private $customerServices;
    use ResponseTraits;

    public function __construct(CustomerServices $customerServices)
    {
        $this->customerServices = $customerServices;
    }

    public function index(IndexCustomerRequest $indexCustomerRequest): JsonResponse
    {
       $response = $this->customerServices->index($indexCustomerRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function store(StoreCustomerRequest $storeCustomerRequest): JsonResponse
    {
        $response = $this->customerServices->store($storeCustomerRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function update(UpdateCustomerRequest $storeCustomerRequest): JsonResponse
    {
        $response = $this->customerServices->update($storeCustomerRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function indexCustomerType(CustomerTypeRequest $CustomerTypeRequest): JsonResponse
    {
        $response = $this->customerServices->indexCustomerType($CustomerTypeRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function storeCustomerType(StoreCustomerTypeRequest $storeCustomerTypeRequest): JsonResponse
    {
        $response = $this->customerServices->storeCustomerType($storeCustomerTypeRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function delete($id): JsonResponse
    {
        $response = $this->customerServices->delete($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function show(ShowCustomerRequest $showCustomerRequest): JsonResponse
    {
        $response = $this->customerServices->show($showCustomerRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');

    }

    public function deleteCustomerType($id): JsonResponse
    {
        $response = $this->customerServices->deleteCustomerType($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function deleteBusinessSegment($id): JsonResponse
    {
        $response = $this->customerServices->deleteBusinessSegment($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function indexBusinessSegment(): JsonResponse
    {
        $response = $this->customerServices->indexBusinessSegment();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function storeBusinessSegment(StoreBusinessSegmentRequest $storeBusinessSegmentRequest): JsonResponse
    {
        $response = $this->customerServices->storeBusinessSegment($storeBusinessSegmentRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function showBusinessSegment(ShowBusinessSegmentRequest $showBusinessSegmentRequest): JsonResponse
    {
        $response = $this->customerServices->showBusinessSegment($showBusinessSegmentRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function showCustomerType(ShowCustomerTypeRequest $showCustomerTypeRequest): JsonResponse
    {
        $response = $this->customerServices->showCustomerType($showCustomerTypeRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function fetchCustomerType(): JsonResponse
    {
        $response = $this->customerServices->fetchAllCustomerType();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function getPriceType(IndexPriceTypeRequest $indexPriceTypeRequest): JsonResponse
    {
        $response = $this->customerServices->getPriceType($indexPriceTypeRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }


}
