<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexDashboardRequest;
use App\Services\DashBoardServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    use ResponseTraits;
    private $dashBoardServices;
    public function __construct(DashBoardServices $dashBoardServices)
    {
        $this->dashBoardServices = $dashBoardServices;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $response = $this->dashBoardServices->index();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function fetchAllDebtorStillOwing(IndexDashboardRequest $indexDashboardRequest)
    {
        $response = $this->dashBoardServices->fetchAllDebtorStillOwing($indexDashboardRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }
}
