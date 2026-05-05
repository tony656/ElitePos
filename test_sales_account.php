<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\salsModel;
use Illuminate\Support\Facades\DB;

echo "=== Sales Account Data Check ===\n\n";

// Check distinct account values in sales table
$accounts = salsModel::select('account')->distinct()->pluck('account');
echo "Distinct 'account' values in sales table:\n";
foreach ($accounts as $acc) {
    echo "  '{$acc}' (type: " . gettype($acc) . ")\n";
}

// Check a few sales records
echo "\nSample sales records:\n";
$sales = salsModel::select('sales_id', 'account', 'totalPrice')->take(5)->get();
foreach ($sales as $sale) {
    echo "  Sales ID: {$sale->sales_id}, Account: '{$sale->account}', Total: {$sale->totalPrice}\n";
}

// Check if any sales have empty account
$emptyAccount = salsModel::where('account', '')->count();
$nullAccount = salsModel::whereNull('account')->count();
echo "\nSales with empty account: {$emptyAccount}\n";
echo "Sales with NULL account: {$nullAccount}\n";