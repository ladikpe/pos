<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoyaltySettingRequest;
use App\Services\LoyaltyServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;

class LoyaltyController extends Controller
{
    use ResponseTraits;

    protected $loyaltyServices;
    public function __construct(LoyaltyServices $loyaltyServices)
    {
        $this->loyaltyServices = $loyaltyServices;
    }

    public function index(): JsonResponse
    {
        $response = $this->loyaltyServices->index();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true,
            responseCode: $response['statusCode'] ?? 200,
            message: $response['message'] ?? ''
        );
    }

    public function store(StoreLoyaltySettingRequest $storeLoyaltySettingRequest): JsonResponse
    {
        $response = $this->loyaltyServices->addSettings($storeLoyaltySettingRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true,
            responseCode: $response['statusCode'] ?? 200,
            message: $response['message'] ?? ''
        );
    }
}
