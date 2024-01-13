<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

  public function inventory()
  {
      return $this->hasOne(Inventory::class, 'id', 'inventory_id');
  }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function transactions()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'order_id');
    }


}
