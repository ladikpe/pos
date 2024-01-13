<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentBreakDownResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'transaction_id' => $this->transaction_id,
            'transaction_mode_id' => $this->transaction_mode_id,
            'transaction_mode' => $this->transaction_mode,
            'amount' => $this->amount,
            'transaction_reference_number' => $this->transaction_reference_number,
            'payment_type_name' => $this->payment_type_name,
            'description' => $this->description,
        ];
    }
}
