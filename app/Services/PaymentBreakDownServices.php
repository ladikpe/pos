<?php

namespace App\Services;

use App\Events\PaymentBreakDown;
class PaymentBreakDownServices
{
    public function storePaymentBreakDown($list)
    {
        PaymentBreakDown::dispatch($list);
    }
}


