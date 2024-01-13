<?php

namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Http\Resources\TransactionModeResource;
use App\Models\Pos;
use App\Models\TransactionMode;
use App\Traits\ResponseTraits;
use Exception;

class TransactionServices
{
    use ResponseTraits;
    protected $transactionMode;
    protected $pos;

    public function __construct(TransactionMode $transactionMode, Pos $pos){
        $this->transactionMode = $transactionMode;
        $this->pos = $pos;
    }

    public function index(): array
    {
        return [
            'message' => 'All Payment Mode Successfully',
            'data' => TransactionModeResource::collection($this->transactionMode->all()->sortByDesc('id', )),
            'status' => true,
            'statusCode' => 200
        ];
    }

    public function storeOrUpdate(array $data)
    {
        try{
            if(isset($data['id'])){
                $transaction_mode = $this->transactionMode->firstTransactionMode($data['id']);
                $transaction_mode->update($data);
                return [
                    'data' => new TransactionModeResource($transaction_mode),
                    'message' => 'Transaction Mode Updated Successfully',
                    'status' => true,
                    'statusCode' => 200,
                ];
            }
            $transaction_mode = $this->transactionMode->updateOrCreate(['transaction_mode' => $data['transaction_mode']]);
            return [
                'data' => new TransactionModeResource($transaction_mode),
                'message' => 'Transaction Mode Added Successfully',
                'statusCode' => 200 ,
                'status' => true
            ];
        }
        catch(Exception $exception) {
                return $exception->getMessage();
        }

    }

    public function delete($id)
    {
            CheckingIdHelpers::preventIdDeletion((int)$id, [1,2,3,4,5,6]);
            try{
                 $this->transactionMode->firstTransactionMode($id)->delete();
                return [
                     'data' => null,
                     'message' => 'Transaction Mode Deleted Successfully',
                     'statusCode' => 200,
                     'status' => true
                ];
            }
            catch(Exception $exception) {
                    return $exception->getMessage();
            }

        }

    public function show(array $data): array
    {
        return [
               'data' => new TransactionModeResource($this->transactionMode->findOrFail($data['id'])),
               'message' => 'Single Payment Mode Selected',
               'statusCode' => 200,
               'status' => true
        ];
    }

    public function fetchAllPos() : array
    {
        $pos = $this->pos->select('id', 'name')->get();
        return [
            'data' => $pos,
            'message'  => 'All Pos Selected',
            'statusCode' => 200,
            'status' => true
       ];
}

    public function storePos(array $data)
    {
        try{
            if(isset($data['id'])){
                $pos = $this->pos->firstRecord($data['id']);
                $pos->update($data);
                return [
                    'data' => $pos,
                    'message' => 'Pos Updated Successfully',
                    'status' => true,
                    'statusCode' => 200,
                ];
            }

            $pos = $this->pos->updateOrCreate(['name' => $data['name']]);
            return [
                'data' => $pos,
                'message' => 'POS Added Successfully',
                'statusCode' => 200 ,
                'status' => true
            ];
        }
        catch(Exception $exception){
            return $exception;
        }
    }

    public function deletePos($id)
    {
        try{
            $this->pos->firstRecord($id)?->delete();
            return [
                'data' => null,
                'message' => 'POS Deleted Successfully',
                'statusCode' => 200,
                'status' => true
            ];
        }
        catch(Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function showPos(array $data): array
    {
        $pos = $this->pos->firstRecord($data['id']);
        return [
            'data' => $pos,
            'message' => 'Single POS Data Retrieved Successfully',
            'statusCode' => 200,
            'status' => true
        ];
    }

}
