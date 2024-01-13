<?php

namespace App\Services;

use App\Events\InventoryReStockHistory;
use App\Helpers\CheckingIdHelpers;
use App\Http\Resources\InventoryResource;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use App\Traits\ResponseTraits;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;


class InventoryServices
{
    use ResponseTraits;
    protected $inventories;

    public function __construct(Inventory $inventories)
    {
        $this->inventories = $inventories;
    }

    public function index(array $data): array
    {
        $categoryId = $data['category_id']  ?? null;
        $inventories = $this->inventories->with('categories')
                                    ->orderByDesc('id')
              ->when($categoryId == 1, function ($query) use ($categoryId){
                     return $query->whereHas('categories', function ($q) use ($categoryId) {
                            return $q->where('id', $categoryId);
                     });
               })
              ->when($categoryId == 2, function ($query) use ($categoryId){
                     return $query->whereHas('categories', function ($q) use ($categoryId) {
                            return $q->where('id','>=' ,$categoryId);
                     });
               });

        if(isset($data['search']))
        {
            $search = $data['search'];
            $inventories = $this->inventories->where(function ($q) use ($search){
                                             $q->OrWhere('id', 'LIKE' ,  '%' . $search. '%' )
                                                ->OrWhere('name','LIKE' ,  '%' . $search. '%' )
                                                ->OrWhere('barcode','LIKE' ,  '%' . $search. '%' );
            });
        }
        return [
            'data' => $inventories->paginate(10),
            'message' => "All Inventories Have Been Retrieved Successfully",
            'statusCode' => 200,
            'status' => true
        ];
    }

    public function storeInventories(array $data): array
    {
        $inventory = $this->inventories->create(
            array_merge($data,['branch_id' => 1])
        );
        return  [
            'data' => new InventoryResource($inventory) ,
             'message' => "Inventory was stored successfully",
            'statusCode' => 200,
            'status' => true
        ];
    }


    public function editInventories(array $data)
    {
        try{
            $inventories = $this->inventories->where('id', $data['id']);
            if(!$inventories->exists()){
                return [
                    'data' => '',
                    'message' => 'inventory not found',
                    'statusCode' =>  401,
                    'status' => false
                ];
            }

            $inventories = $inventories->first();
            return [ 'data' => new InventoryResource($inventories),
                     'message' => "Successfully retrieved Inventory",
                     'statusCode' =>  200,
                     'status' => true
            ];
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function updateInventory(array $data){
        try{
            $inventory = $this->inventories->where('id', $data['id'])->first();
            $inventory->update($data);
            return [
              "data" =>  new InventoryResource($inventory),
              "message" => "Inventory Was Updated Successfully",
              "statusCode" =>  200,
              "status" => true
            ];
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function inventoryQuantityRestock(array $data): array
    {
        $inventory = $this->inventories->firstRecord($data['id']);
        $newQuantity = (int)$data['quantity'] + $inventory->quantity;
        $inventory->update(['quantity'  => $newQuantity]);
        (new StockServices())->inventoryRestockHistory($inventory, $data['quantity'], $inventory['price']);
        return [
            'message' => 'Quantity Added SuccessFully',
            'data' => $inventory->refresh(),
            'status' => true,
            'statusCode' => 200
        ];
    }

    public function destroy($id){
        try{
            $id = (int)$id;
            $checkInventory = OrderDetail::where('inventory_id', $id);
            if ($checkInventory?->first()) {
                return [
                    'data' => null,
                   'message' => 'inventory already used in sales cant be deleted',
                   'statusCode' =>  200,
                   'status' => true
                ];
            }

            $this->inventories->firstRecord($id)?->delete();
            return [
                'data' => null,
                'message' => "Inventory was deleted successfully",
                'statusCode' =>  200,
                'status' => true
            ];
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

}
