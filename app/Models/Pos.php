<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function firstRecord($id)
    {
        return $this->where('id', $id)->first();
    }
}
