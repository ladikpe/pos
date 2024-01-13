<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentBreakDown extends Model
{
    use HasFactory;


    protected $guarded = ['id'];


    public function transactionMode()
    {
        return $this->hasOne(TransactionMode::class, 'id', 'transaction_mode_id');
    }

    public function transactions()
    {
        return $this->belongsTo(Transaction::class, 'id', 'transaction_id' );
    }
}
