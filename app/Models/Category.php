<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'low_stock_alert', 'gas_quantity'];

    Public CONST REFILL = 1;

    public static function getCategory($id)
    {
        return self::where('id', $id)->first();
    }

    public function getCategoryName($name)
    {
        return $this->where('name', $name)->first();
    }

}
