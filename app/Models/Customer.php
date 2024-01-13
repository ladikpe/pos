<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $appends = ['loyalty_balance'];
    public function customerType()
   {
       return $this->hasOne(CustomerType::class, 'id', 'customer_type_id');
   }

   public function businessSegment()
   {
       return $this->hasOne(BusinessSegment::class, 'id', 'business_segment_id');
   }

   public function transaction()
   {
       return $this->hasMany(Transaction::class, 'customer_id', 'id');
   }

   public function order()
   {
       return $this->hasMany(Order::class, 'customer_id', 'id');
   }

   public function firstRecord($parameter)
   {
       return $this->where('id', $parameter)->with('customerType')->firstOrFail();
   }

   public function firstCustomer($id)
   {
       return $this->where('id', $id)->first();
   }

    public function loyalty(): BelongsToMany
    {
        return $this->belongsToMany(
            Loyalty::class,  LoyaltyCustomer::class,
            'customer_id', 'loyalty_id'
        )->withTimestamps();
    }

    public function loyaltyDeduction(): HasMany
    {
        return $this->hasMany(LoyaltyDeduction::class, 'customer_id', 'id');
    }

    public function getLoyaltyBalanceAttribute()
    {
        $totalLoyaltyDeduction = $this->loyaltyDeduction()->sum('amount_deducted');
        $totalLoyaltyPoints = $this->loyalty()->sum('amount_gain_by_points');
        return $totalLoyaltyPoints - $totalLoyaltyDeduction;
    }



}
