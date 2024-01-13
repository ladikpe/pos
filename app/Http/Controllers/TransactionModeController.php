<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexShowPosRequest;
use App\Http\Requests\ShowTransactionModeRequest;
use App\Http\Requests\StorePosRequest;
use App\Http\Requests\StoreTransactionModeRequest;
use App\Services\TransactionServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;

class TransactionModeController extends Controller
{
    use ResponseTraits;
    protected $transactionServices;
    public function __construct(TransactionServices $transactionServices)
    {
        $this->transactionServices = $transactionServices;
    }


    public function index() : JsonResponse
    {
        $response =  $this->transactionServices->index();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }


    public function store(StoreTransactionModeRequest $storeTransactionModeRequest) : JsonResponse
    {
        $response = $this->transactionServices->storeOrUpdate($storeTransactionModeRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function show(ShowTransactionModeRequest $showTransactionModeRequest) : JsonResponse
    {
        $response =  $this->transactionServices->show($showTransactionModeRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function delete($id): JsonResponse
    {
        $response =  $this->transactionServices->delete($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function storePos(StorePosRequest $storePosRequest) : JsonResponse
    {
        $response =  $this->transactionServices->storePos($storePosRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function deletePos($id): JsonResponse
    {
        $response =  $this->transactionServices->deletePos($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function showPos(IndexShowPosRequest $indexShowPosRequest) : JsonResponse
    {
        $response =  $this->transactionServices->showPos($indexShowPosRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function fetchAllPos(): JsonResponse
    {
        $response = $this->transactionServices->fetchAllPos();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

}
