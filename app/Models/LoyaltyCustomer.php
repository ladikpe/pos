<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyCustomer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];



    public function customers(): belongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function loyalties(): belongsTo
    {
        return $this->belongsTo(Loyalty::class, 'loyalty_id', 'id');
    }

}
