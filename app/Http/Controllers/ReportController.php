<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexReportCashierDaily;
use App\Http\Requests\IndexReportCashierDailyRequest;
use App\Http\Requests\IndexReportCustomerDebt;
use App\Http\Requests\IndexReportOrderDetailRequest;
use App\Http\Requests\IndexReportRequest;
use App\Http\Requests\IndexReportUserRequest;
use App\Http\Requests\IndexAccessoriesDailyReportRequest;
use App\Services\ReportServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    use ResponseTraits;
    protected $reportServices;

    public function __construct(ReportServices $reportServices){
         $this->reportServices = $reportServices;
     }


     public function dailyReport(IndexReportRequest $indexReportRequest): JsonResponse
     {
         $response = $this->reportServices->dailyReport($indexReportRequest->validated());
         return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '');
     }

     public function transactionReport(IndexReportRequest $indexReportRequest): JsonResponse
     {
         $response =  $this->reportServices->transactionReport($indexReportRequest->validated());
         return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '');
     }

     public function cashierSalesReport(IndexReportRequest $indexReportRequest): JsonResponse
     {
         $response =  $this->reportServices->cashierReport($indexReportRequest->validated());
         return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '');
     }

     public function fetchAllUser(IndexReportUserRequest $indexReportUserRequest): JsonResponse
     {
         $response = $this->reportServices->fetchAllUser($indexReportUserRequest->validated());
         return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '', responseCode: $response['statusCode']);
     }

    public function debtorReport(IndexReportCustomerDebt $indexReportCustomerDebt): JsonResponse
    {
        $response = $this->reportServices->debtorReport($indexReportCustomerDebt->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '', responseCode: $response['statusCode']);
    }

    public function cashierDailyReport(IndexReportCashierDailyRequest $indexReportCashierDailyRequest): JsonResponse
    {
        $response = $this->reportServices->cashierDailyReport($indexReportCashierDailyRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '', responseCode: $response['statusCode']);
    }

    public function orderDetailReport(IndexReportOrderDetailRequest $indexReportOrderDetailRequest): JsonResponse
    {
        $response = $this->reportServices->orderDetailReport($indexReportOrderDetailRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '', responseCode: $response['statusCode']);
    }


    public function accessoriesDailyReport(IndexAccessoriesDailyReportRequest $indexAccessoriesDailyReportRequest): JsonResponse
    {
       $response = $this->reportServices->accessoriesDailyReport($indexAccessoriesDailyReportRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '', responseCode: $response['statusCode']);   
    }


}
