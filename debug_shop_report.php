<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\accountModel;
use App\Models\salsModel;
use App\Models\expensesModel;
use App\Models\debtsModel;
use App\Models\recevingModel;
use App\Models\madeni;
use App\Models\cashSubmitModel;
use App\Models\BankingTransfer;
use App\Models\BankingChip;
use App\Models\TransactionBalance;
use App\Models\TransactionDiscrepancy;

// Simulate the request
$req = new \Illuminate\Http\Request();
$req->merge(['date' => date('Y-m-d')]);

// Get shops
$user = \Illuminate\Support\Facades\Auth::loginUsingId(1); // Admin user
$allShops = accountModel::select('id', 'name', 'location')
    ->where('created_at', '<=', now())
    ->orderBy('created_at', 'desc')
    ->get();

echo "Total Shops: " . $allShops->count() . PHP_EOL;
if ($allShops->isEmpty()) {
    echo "NO SHOPS FOUND!" . PHP_EOL;
    exit;
}

$shopIds = $allShops->pluck('id')->toArray();
echo "Shop IDs: " . implode(', ', $shopIds) . PHP_EOL;

// Date range
$reportDate = \Carbon\Carbon::now();
$start_date = $reportDate->copy()->startOfDay()->format('Y-m-d H:i:s');
$end_date = $reportDate->copy()->endOfDay()->format('Y-m-d H:i:s');
echo "Date range: $start_date to $end_date" . PHP_EOL;

// Test sales query
echo "\n=== Testing Sales Query ===" . PHP_EOL;
$salesData = salsModel::whereIn('account', $shopIds)
    ->whereBetween('created_at', [$start_date, $end_date])
    ->where('status', '!=', 'Return')
    ->where(function($q) { $q->where('salesName', '!=', '')->orWhereNull('salesName'); })
    ->selectRaw('account, COUNT(DISTINCT sales_id) as total_transactions, SUM(totalPrice) as total_sales')
    ->groupBy('account')
    ->get();

echo "Sales data count: " . $salesData->count() . PHP_EOL;
foreach($salesData as $row) {
    echo "  Shop {$row->account}: transactions={$row->total_transactions}, sales={$row->total_sales}" . PHP_EOL;
}

// Test if any shops have sales today
echo "\n=== Raw Sales Check ===" . PHP_EOL;
$totalSalesToday = salsModel::whereBetween('created_at', [$start_date, $end_date])->count();
echo "Total sales today (any shop): $totalSalesToday" . PHP_EOL;

if ($totalSalesToday > 0) {
    $sampleShop = salsModel::whereBetween('created_at', [$start_date, $end_date])->first();
    echo "Sample sales record account: " . $sampleShop->account . PHP_EOL;
    echo "Sample sales status: " . $sampleShop->status . PHP_EOL;
}

// Test expenses
echo "\n=== Testing Expenses Query ===" . PHP_EOL;
$expensesData = expensesModel::whereIn('account', $shopIds)
    ->whereBetween('created_at', [$start_date, $end_date])
    ->selectRaw('account, COALESCE(SUM(amount), 0) as total_expenses')
    ->groupBy('account')
    ->get();
echo "Expenses data count: " . $expensesData->count() . PHP_EOL;

echo "\n=== DONE ===" . PHP_EOL;