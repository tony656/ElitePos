<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ProductsController;

class ClearProductCache extends Command
{
    protected $signature = 'cache:clear-products {account?}';
    protected $description = 'Clear product cache for specific account or all';
    
    public function handle()
    {
        $account = $this->argument('account');
        ProductsController::clearProductCache($account);
        $this->info('Product cache cleared successfully');
    }
}
