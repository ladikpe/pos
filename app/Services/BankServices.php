<?php

namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Http\Resources\BankResource;
use App\Models\Order;
use App\Traits\ResponseTraits;
use App\Models\Bank;



class BankServices
{

    use ResponseTraits;

    public function index(array $data): array
    {

        $banks = Bank::query();

        if(isset($data['name']))
        {
            $banks = $banks->where('acn_name', 'like', '%' . $data['name'] . '%');
        }

        return [
              'data' => $banks->orderByDesc('id')
                                ->paginate(10),
              'status' => true,
              'statusCode' => 200,
              'message' => "All Banks Have Been Retrieved Successfully"
          ];
    }



    public function fetchAllBanks(array $data): array
    {
        $limit = $data['limit'] ?? 10;
        $banks = Bank::query();
        $search = $data['name'] ?? null;
        if(isset($search)) {
            $banks = $banks->where('acn_name', 'LIKE', "%{$search}%");
        }
        return [
            'data' => $banks->orderByDesc('id')
                ->paginate($limit),
            'status' => true,
            'statusCode' => 200,
            'message' => "All Banks Have Been Retrieved Successfully"
        ];
    }

    public function storeBank(array $data){
        try{
            if(isset($data['id'])){
                $bank = Bank::where('id', $data['id'])->first();
                $bank->update($data);
                return [
                       'data' => new BankResource($bank),
                       'message' => 'Bank Was Updated Successfully',
                       'statusCode' => 200,
                       'status' => true,
                ];
            }
            $checkAccountNumber = Bank::where('acn_no', $data['acn_no'])->first();
            if($checkAccountNumber)
            {
                return [
                    'data' => null,
                    'status' => true,
                    'statusCode' => 401,
                    'message' => 'Account Number Already Exists'
                ];
            }
            $bank = Bank::create($data);
            return [
                    'data' => new BankResource($bank),
                    'message' => "Bank Was Added Successfully",
                    'statusCode' => 200,
                    'status' => true
            ];
       }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function editBank(array $data): array
    {
            $bank = Bank::where('id', $data['id'])->firstorFail();
            return [
                        'data' => new BankResource($bank),
                        'message' => "Single Bank Record Successfully Retrieved",
                        'statusCode' => 200,
                        'status' => true
            ];
    }

    public function destroy($id){
        $checkIdExist = Order::where('branch_id', $id)->exists();
        if($checkIdExist){
            return [
                'data' => null,
                'message' => "Bank has Been used in a transaction already",
                'statusCode' => 200,
                'status' => true
            ];
        }
        try{
            $Bank = Bank::findorFail($id);
            $Bank->delete();
            return [
                'data' => null,
                'message' => "Bank was deleted successfully",
                'statusCode' => 200,
                'status' => true
            ];
        }
        catch(\Exception $e){
            return $e->getMessage();
        }
    }

}
