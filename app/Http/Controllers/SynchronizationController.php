<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexSynchronizationRequest;
use App\Services\Synchronization\DataSynchronizationServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SynchronizationController extends Controller
{
    use ResponseTraits;
    public function __construct(protected DataSynchronizationServices $dataSynchronizationServices){

    }

    public function synchronizationToDb(IndexSynchronizationRequest $indexSynchronizationRequest): JsonResponse
    {
        $response = $this->dataSynchronizationServices->synchronizeDailyReport($indexSynchronizationRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }
}
