<?php

namespace App\Models;

use App\Http\Resources\CustomerResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    // todo add with trash
    public function transactionMode(): BelongsTo
    {
        return $this->belongsTo(TransactionMode::class, 'transaction_mode_id');
    }

    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }


    public function ordersTrashed(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')->onlyTrashed();
    }

    public function getBranch($branchId)
    {
        return $this->where('branch_id',$branchId);
    }

    public function paymentBreakDown(): HasMany
    {
        return $this->hasMany(PaymentBreakDown::class, 'transaction_id', 'id');
    }

}
