<?php

namespace App\Providers;

use App\Events\InventoryReStockHistory;
use App\Events\InventoryStock;
use App\Events\PaymentBreakDown;
use App\Listeners\StoreInventoryRestockHistory;
use App\Listeners\StorePaymentBreakDown;
use App\Listeners\UpdateInventoryStock;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        InventoryStock::class => [
            UpdateInventoryStock::class,
        ],

        InventoryReStockHistory::class => [
            StoreInventoryRestockHistory::class,
        ],
        
        PaymentBreakDown::class => [
            StorePaymentBreakDown::class
        ],
        
    ];


    public function boot()
    {
        //
    }
}
