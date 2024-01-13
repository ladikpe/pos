<?php

namespace App\Http\Controllers;

use App\Http\Requests\customerDebtPaymentRequest;
use App\Http\Requests\IndexDebtorCustomerOrderRequest;
use App\Http\Requests\IndexDebtorRequest;
use App\Http\Requests\ShowDebtorRequest;
use App\Http\Requests\StoreDebtorRequest;
use App\Services\DebtorServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;

class DebtorController extends Controller
{
    use ResponseTraits;
    protected $debtorServices;
    public function __construct(DebtorServices $debtorServices)
    {
          $this->debtorServices = $debtorServices;
    }

    public function index(IndexDebtorRequest $indexDebtorRequest)
    {
        $response = $this->debtorServices->index($indexDebtorRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function store(StoreDebtorRequest $storeDebtorRequest)
    {
        $response = $this->debtorServices->store($storeDebtorRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function show(ShowDebtorRequest $showDebtorRequest)
    {
        $response = $this->debtorServices->show($showDebtorRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function delete($id)
    {
        $response = $this->debtorServices->delete($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function customerDebtPayment(customerDebtPaymentRequest $customerDebtPaymentRequest)
    {
        $response = $this->debtorServices->customerDebtPayment($customerDebtPaymentRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');

    }

   public function showDebtor(showDebtorRequest $showDebtorRequest)
    {
        $response = $this->debtorServices->customerDebtPayment($showDebtorRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function fetchAllOrders(IndexDebtorCustomerOrderRequest $indexDebtorCustomerOrderRequest)
    {
        $response = $this->debtorServices->fetchAllOrders($indexDebtorCustomerOrderRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }


}
