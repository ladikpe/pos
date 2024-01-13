<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authServices;
    use ResponseTraits;
    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
    }

    public function login(LoginRequest $loginRequest): JsonResponse
    {
        $response = $this->authServices->login($loginRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        $response = $this->authServices->register($registerRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function refresh(): JsonResponse
    {
        return $this->authServices->refresh();
    }

    public function forgetPassword(ForgetPasswordRequest $forgetPasswordRequest): JsonResponse
    {
        $response = $this->authServices->forgetPassword($forgetPasswordRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function resetPassword(ResetPasswordRequest $resetPasswordRequest): JsonResponse
    {
        $response = $this->authServices->resetPassword($resetPasswordRequest->validated());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }
}
