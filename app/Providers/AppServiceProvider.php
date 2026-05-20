<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use App\Models\salsModel;
use App\Models\debtsModel;
use App\Models\expensesModel;
use App\Models\recevingModel;
use App\Models\madeni;
use App\Models\BankingTransfer;
use App\Models\BankingChip;
use App\Models\cashSubmitModel;
use App\Observers\TransactionObserver;

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
        // Set default string length to avoid MySQL index issues with utf8mb4
        Schema::defaultStringLength(191);
        
        // Register observers for automatic transaction tracking
        salsModel::observe(TransactionObserver::class);
        debtsModel::observe(TransactionObserver::class);
        expensesModel::observe(TransactionObserver::class);
        recevingModel::observe(TransactionObserver::class);
        madeni::observe(TransactionObserver::class);
        BankingTransfer::observe(TransactionObserver::class);
        BankingChip::observe(TransactionObserver::class);
        cashSubmitModel::observe(TransactionObserver::class);
    }
}
