<?php

namespace App\Services;

use App\Events\InventoryReStockHistory;
use App\Events\InventoryStock;
use App\Models\Inventory;
use App\Models\Setting;
use App\Models\User;
use App\Models\Category;
use App\Notifications\LowStockNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;


class StockServices
{

    /**
     * @throws Exception
     */

    public function checkStockLevels($InventoryData)
    {

        foreach($InventoryData as $value)
        {
            if (Inventory::where('id', $value['id'])->first()->category_id > 1)
            {
                $item = Inventory::getFirstRecord($value['id']);
                $available_quantity = $item->quantity;
                $selectedQuantity = $value['quantity'];
                $low_stock_level = Setting::getLowStockLevelAlert();

                if(abs($selectedQuantity - $available_quantity) < intVal($low_stock_level)){
                    abort(401,'Item Stock Is Less Than Quantity Selected For Purchase:- '.$item['name']);
                }

                if($selectedQuantity > $available_quantity)
                {
                    abort(401,'stock Has Finished or Very Low:- '.$item['name'] );
                }

                if(intVal($available_quantity) <= intVal($low_stock_level))
                {
                       abort(401, 'Item Stock is Low:- '.$item['name']);
                }

                $currentQuantity = $available_quantity - $selectedQuantity;
                InventoryStock::dispatch($item, $currentQuantity);
            }

            if(Inventory::where('id', $value['id'])->first()->category_id == 1){

                $item = Category::first();
                $available_quantity = $item->gas_quantity;
                $selectedQuantity = $value['quantity'];
                $low_stock_level = Setting::first()->gas_limit ?? 10;

                if(abs($selectedQuantity - $available_quantity) < intVal($low_stock_level)){
                    abort(401,'Gas Refill Is Less Than Quantity Selected For Purchase:- '.$item['name']);
                }

                if($selectedQuantity > $available_quantity)
                {
                    abort(401,'Gas Refill Cylinder Is Finished or Very Low:- '.$item['name'] );
                }

                if(intVal($available_quantity) <= intVal($low_stock_level))
                {
                       abort(401, 'Gas Refill Cylinder is Low:- '.$item['name']);
                }

                    $currentQuantity = $available_quantity - $selectedQuantity;
                    $item->update(['gas_quantity' => $currentQuantity]);
                }
            }
        }

    public function lowStockNotification($message): void
    {
        $AdminUser = User::adminRole();
        $AdminUserId = $AdminUser->pluck('id');
        DB::table('users')->whereIn('id', $AdminUserId)->orderBy('id')->chunk(10, function (Collection $users) use ($AdminUser, $message) {
            foreach ($users as $user) {
                Notification::sendNow($AdminUser, new LowStockNotification($message, $user));
            }
        });
    }

    public function message($item): array
    {
        return  [
            'body' => 'This Inventory Item Stock is Low',
            'item' => $item->name,
            'quantity' => $item->quantity,
        ];
    }

    public function inventoryRestockHistory($inventory, $quantity, $price)
    {
        $branch_id = auth()->user()->branch_id;
        InventoryReStockHistory::dispatch($inventory, $quantity, $price, $branch_id);
    }

}
