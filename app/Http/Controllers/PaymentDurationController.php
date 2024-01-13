<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowPaymentDurationRequest;
use App\Http\Requests\StorePaymentDurationRequest;
use App\Services\PaymentDurationServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;

class PaymentDurationController extends Controller
{

    use ResponseTraits;
    private $paymentDurationServices;

    public function __construct(PaymentDurationServices $paymentDurationServices)
    {
        $this->paymentDurationServices = $paymentDurationServices;
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->paymentDurationServices->index();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function show(ShowPaymentDurationRequest $showPaymentDurationRequest): \Illuminate\Http\JsonResponse
    {
        $response = $this->paymentDurationServices->show($showPaymentDurationRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function store(StorePaymentDurationRequest $storePaymentDurationRequest): \Illuminate\Http\JsonResponse
    {
        $response = $this->paymentDurationServices->store($storePaymentDurationRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->paymentDurationServices->delete($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }
}
