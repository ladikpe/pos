<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexBankRequest;
use App\Http\Requests\IndexFetchBankRequest;
use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\BankServices;



class BankController extends Controller
{
    use ResponseTraits;
    protected $bankServices;
    public function __construct(BankServices $bankServices)
    {
        $this->bankServices = $bankServices;
    }

    public function index(IndexBankRequest $indexBankRequest): JsonResponse
    {
        $response = $this->bankServices->index($indexBankRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function store(StoreBankRequest $storeBankRequest): JsonResponse
    {
        $response = $this->bankServices->storeBank($storeBankRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function edit(UpdateBankRequest $updateBankRequest): JsonResponse
    {
        $response = $this->bankServices->editBank($updateBankRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function delete($id): JsonResponse
    {
        $response = $this->bankServices->destroy($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function fetchAllBanks(IndexFetchBankRequest $indexFetchBankRequest): JsonResponse
    {
        $response = $this->bankServices->fetchAllBanks($indexFetchBankRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

}


