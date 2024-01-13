<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\CategoryServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\JsonResponse;


class CategoryController extends Controller
{
    use ResponseTraits;

    protected $categoryServices;
    public function __construct(CategoryServices $categoryServices)
    {
        $this->categoryServices = $categoryServices;
    }

    public function index(): JsonResponse
    {
        $response = $this->categoryServices->index();
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '');
    }

    public function store(StoreCategoryRequest $storeCategoryRequest): JsonResponse
    {
        $response = $this->categoryServices->storeCategory($storeCategoryRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '');
    }

    public function edit(EditCategoryRequest $editCategoryRequest): JsonResponse
    {
        $response = $this->categoryServices->editCategory($editCategoryRequest->all());
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '');
    }

    public function delete($id): JsonResponse
    {
        $response = $this->categoryServices->destroy($id);
        return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '');
    }

    public function getAllCategory(): JsonResponse
    {
         $response = $this->categoryServices->getAllCategory();
         return $this->responseJson(data: $response['data'], status: $response['status'] ?? true, message: $response['message'] ?? '');
    }
}
