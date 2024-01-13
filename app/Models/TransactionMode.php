<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionMode extends Model
{
    public const TransactionModePayLater = 4;
    use HasFactory;
    protected $guarded = ['id'];

    public function getTransactionMode($id)
    {
        return $this->where('id', $id)->first()->transaction_mode;
    }

    public function firstTransactionMode($id)
    {
        return $this->where('id', $id)->first();
    }
}
