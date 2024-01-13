<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\ChangeUserRoleRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ShowUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserIndexRequest;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;
use App\Services\UserServices;


class UserController extends Controller
{

    protected $userServices;
    use ResponseTraits;
    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function index(UserIndexRequest $userIndexRequest): \Illuminate\Http\JsonResponse
    {
        $response = $this->userServices->index($userIndexRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function show(ShowUserRequest $showUserRequest): \Illuminate\Http\JsonResponse
    {
        $response = $this->userServices->showUser($showUserRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function update(UpdateUserRequest $updateUserRequest): \Illuminate\Http\JsonResponse
    {
        $response = $this->userServices->updateProfile($updateUserRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function changePassword(ChangePasswordRequest $changePasswordRequest): \Illuminate\Http\JsonResponse
    {
        $response = $this->userServices->changePassword($changePasswordRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function changeStatus(ChangeStatusRequest $changePasswordRequest): \Illuminate\Http\JsonResponse
    {
        $response = $this->userServices->changeStatus($changePasswordRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }


    public function authUser(): \Illuminate\Http\JsonResponse
    {
        $response = $this->userServices->authenticatedUser();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function changeUserRole(ChangeUserRoleRequest  $changeUserRoleRequest): \Illuminate\Http\JsonResponse
    {
        $response = $this->userServices->changeUserRole($changeUserRoleRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function updateUserProfile(UpdateProfileRequest $updateProfileRequest): \Illuminate\Http\JsonResponse
    {
        $response = $this->userServices->updateUserProfile($updateProfileRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->userServices->delete($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

}
