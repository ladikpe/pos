<?php

namespace App\Models;

use App\Enums\OrderStatusEnums;
use App\Enums\PaymentTypeEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'status' => OrderStatusEnums::class,
        'payment_type' => PaymentTypeEnums::class,
    ];

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function debtor()
    {
        return $this->belongsTo(Debtor::class, 'order_number', 'order_number');
    }


    public function paymentBreakDown()
    {
        return $this->hasMany(PaymentBreakDown::class, 'order_id', 'id');
    }

    public function getOrderById($id)
    {
        return $this->where('id', $id)->with('employee.branch')->firstOrFail();
    }

    public function getOrderDetails($orderNumber)
    {
        return $this->where('branch_id', Auth::user()->branch_id)
                        ->where('order_number', $orderNumber)
                        ->first();
    }

    public function getOrdersWithTrash()
    {
        return self::onlyTrashed()->with(['customer','employee'])->get();
    }

    public function orderDetailTrash()
    {
        return $this->hasMany(OrderDetail::class, 'order_id')->onlyTrashed();
    }

    public function transactionTrash()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'id')->onlyTrashed();
    }


    public function getTrashedOrderById($id)
    {
        return $this->where('id', $id)->onlyTrashed()
                                        ->with(['orderDetailTrash.inventory','transactionTrash.paymentBreakDown', 'employee.branch', 'customer'])
                                        ->firstOrFail();
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
