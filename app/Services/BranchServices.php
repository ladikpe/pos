<?php

namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Http\Resources\BranchResource;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\ResponseTraits;
use App\Models\Branch;



class BranchServices
{

    use ResponseTraits;
    public function index(array $data)
    {
        $branch = Branch::query();
        if(isset($data['name']))
        {
            $search = $data['name'];
            $branch = $branch->where('name', 'like', '%' . $search  . '%');
        }
        return [
            'data' => $branch->orderByDesc('id')->get(),
            'message' => "All Branch Have Been Retrieved Successfully",
            'status' => true,
            'statusCode' => 200
        ];
    }

    public function storeBranch(array $data){
        try{
            $branch = Branch::updateOrCreate([
                'id'   => $data["id"] ?? null,
            ],[
                'name' => $data["name"],
                'address' => $data["address"],
                'phone_no' => $data["phone_no"],
                'email' => $data["email"]
            ]);
            return [
                    'data' =>  new BranchResource($branch),
                    'message' => "Branch was added successfully",
                    'statusCode' => 200,
                    'status' => true,
            ];
        }catch(\Exception $e){
            return $e->getMessage();
        }

    }

    public function editBranch(array $data)
    {
            $branch = Branch::where('id', $data['id'])->firstOrFail();
            return ['data' =>  new BranchResource($branch),
                    'message' => "Single Branch Successfully retrieved",
                    'statusCode' => 200,
                    'status' => true
            ];
    }

    public function destroy($id){
         CheckingIdHelpers::preventIdDeletion((int)$id, [1,2,3,4,5]);
        try{
            $branch = Branch::findorFail($id);
            $branch->delete();
            return [ 'data' => null,
                    'message' => "Branch was deleted successfully",
                    'statusCode' => 200,
                    'status' => true
            ];
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
