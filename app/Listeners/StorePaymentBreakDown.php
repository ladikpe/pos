<?php

namespace App\Listeners;

use App\Events\PaymentBreakDown;
use App\Models\TransactionMode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class StorePaymentBreakDown
{

    public function __construct(protected TransactionMode $transaction_mode)
    {

    }


    public function handle(PaymentBreakDown $event)
    {
         $list =  $event->paymentBreakDownList;
        foreach($list['payment_break_down'] as $key => $item)
         {
                DB::table('payment_break_downs')->insert(
                            ['transaction_id' => $list['transaction_id'],
                            'transaction_reference_number' => $list['reference_number'],
                            'transaction_mode_id' => $item['transaction_mode_id'],
                            'transaction_mode' =>  $this->transaction_mode->getTransactionMode($item['transaction_mode_id']),
                            'amount' =>  $item['amount'],
                            'branch_id' => $list['branch_id'],
                            'payment_type_name' => $item['payment_type_name'] ?? null,
                            'description' => $item['description'] ?? null ,
                             'created_at' => Carbon::now(),
                        ]);
         }
         return true;
    }
}
