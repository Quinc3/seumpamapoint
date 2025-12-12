<?php

namespace App\Providers;

use App\Events\OrderPaid;
use App\Listeners\AutoPrintInvoice;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderPaid::class => [
            AutoPrintInvoice::class,
        ],
    ];

    // ...
}