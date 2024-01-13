<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTransactionV2Resource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'reference_number' => $this->reference_number,
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'branch_id' => $this->branch_id,
            'transaction_date' => $this->transaction_date,
            'payment_break_down' => PaymentBreakDownResource::collection($this->paymentBreakDown),
        ];
    }
}
