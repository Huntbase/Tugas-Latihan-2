<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StockTransfer;
use App\Policies\StockTransferPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    protected $policies = [
        StockTransfer::class => StockTransferPolicy::class,
        // ...policy lain yang sudah ada, biarkan tetap di situ
    ];
}
