<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InventoryReStockHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function inventory(): belongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }

    public function inventoryHistory(){
        return  $this->select('id','inventory_id','quantity','created_at', 'branch_id', 'price')
                       ->with(['inventory:id,name,price,dealer_price,staff_price,crs_price,unit_of_measurement']);
    }
}
