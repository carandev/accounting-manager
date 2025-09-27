<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        Model::unguard();
        Transaction::observe(TransactionObserver::class);
        if (app()->environment("production")) {
            URL::forceScheme("https");
        }
    }
}
