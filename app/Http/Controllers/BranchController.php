<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditBranchRequest;
use App\Http\Requests\IndexBranchRequest;
use App\Http\Requests\StoreBranchRequest;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\BranchServices;

class BranchController extends Controller
{
    use ResponseTraits;

    protected $branchServices;
    public function __construct(BranchServices $branchServices)
    {
        $this->branchServices = $branchServices;
    }

    public function index(IndexBranchRequest $indexBranchRequest): JsonResponse
    {
        $response = $this->branchServices->index($indexBranchRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function store(StoreBranchRequest $storeBranchRequest): JsonResponse
    {
        $response = $this->branchServices->storeBranch($storeBranchRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function edit(EditBranchRequest $editBranchRequest): JsonResponse
    {
        $response = $this->branchServices->editBranch($editBranchRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }

    public function delete($id): JsonResponse
    {
        $response = $this->branchServices->destroy($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, responseCode: $response['statusCode'] ?? 200, message: $response['message'] ?? '');
    }
}
