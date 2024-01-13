<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Loyalty extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer(): BelongsToMany
    {
        return $this->belongsToMany(
            Customer::class,
            LoyaltyCustomer::class,
            'loyalty_id',
            'customer_id'
        )
            ->withTimestamps();
    }
}
