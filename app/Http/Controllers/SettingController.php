<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowSettingsRequest;
use App\Http\Requests\StoreSettingsRequest;
use App\Services\SettingServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;
use App\Http\Requests\GasLimitSettingRequest;


class SettingController extends Controller
{
    use ResponseTraits;
    private $settingServices;

    public function __construct(SettingServices $settingServices)
    {
        $this->settingServices = $settingServices;
    }


    public function show()
    {
        $response = $this->settingServices->show();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');

    }

    public function store(StoreSettingsRequest  $storeSettingsRequest)
    {
        $response = $this->settingServices->store($storeSettingsRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');

    }

    public function addToGas(GasLimitSettingRequest $gasLimitSettingsRequest)
    {
       $response = $this->settingServices->addToGas($gasLimitSettingsRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');

    }


    public function getGasRefillQuantity()
    {
       $response = $this->settingServices->getGasRefillQuantity();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');

    }



}
