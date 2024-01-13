<?php

namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\ResponseTraits;
use App\Models\Category;



class CategoryServices
{

    use ResponseTraits;
    protected $categories;

    public function __construct(Category $categories)
    {
        $this->categories = $categories;
    }

    public function index(): array
    {
        $categories = $this->categories::select('id', 'name', 'created_at')
                            ->orderByDesc('id')
                            ->get();
        return [
                'data' => $categories,
                'message' => "All Categories Have Been Retrieved Successfully",
                'statusCode' => 200,
                'status' => true
        ];
    }
    public function storeCategory(array $data)
    {
        try{
            if(isset($data['id']))
            {
                $category = $this->categories::updateOrCreate(['id'=>$data['id']],['name' => $data['name']]);
                return  [
                    'data' => new CategoryResource($category),
                    'message' => "Category Updated Successfully",
                    'status' => true,
                    'statusCode' => 200,
                ];
            }
            $category = $this->categories->getCategoryName($data['name']);
            if($category)
            {
                return [
                            'message' => 'Category Name Already',
                            'statusCode' => 401,
                            'status' => true,
                            'data' => null
                ];
            }
            $category = $this->categories::create($data);
            return [
                    'data' => new CategoryResource($category),
                    'message' => "Category was added successfully",
                    'statusCode' => 200,
                     'status' => true
            ];
        }

       catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function editCategory(array $data){
        try{
            $category = Category::getCategory($data['id']);
            if(!$category){
                return [
                    "message" => "Category not found",
                    'data' => null,
                     'status' => false,
                     'statusCode' => 402
                ];
            }
            return [ 'data' => new CategoryResource($category),
                    'message' => 'Single Category Successfully retrieved Category',
                    'statusCode' => 200,
                    'status' => true
            ];
        }catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
    public function destroy($id)
    {
       CheckingIdHelpers::preventIdDeletion((int)$id, [1,2,3,4,5]);
        try{
            $Category = Category::findorFail($id);
            $Category->delete();
            return ['data' => null,
                     'message' => 'Category was deleted successfully',
                     'statusCode' => 200,
                     'status' => true
            ];
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function getAllCategory(): array
    {
        $inventoryCategory = Category::get(['id','name','gas_quantity']);
        return [
            'data' => $inventoryCategory,
            'message' => 'Fetch All Category For Inventory Report SuccessFully',
            'status' => true,
            'statusCode' => 200
        ];

    }

}
