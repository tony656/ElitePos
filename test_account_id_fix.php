<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\accountModel;
use App\Models\salsModel;
use App\Models\UserAccount;

echo "=== Account ID Fix Verification ===\n\n";

// Check what's in the database
echo "--- Accounts ---\n";
$accounts = accountModel::all();
foreach ($accounts as $acc) {
    echo "ID: {$acc->id}, Name: '{$acc->name}'\n";
}

echo "\n--- Sales Sample ---\n";
$sales = salsModel::select('sales_id', 'account', 'totalPrice', 'created_at')->take(3)->get();
foreach ($sales as $sale) {
    echo "Sales ID: {$sale->sales_id}, Account: '{$sale->account}', Total: {$sale->totalPrice}\n";
}

echo "\n--- User Account Assignments ---\n";
$userAccounts = UserAccount::all();
foreach ($userAccounts as $ua) {
    $acc = accountModel::find($ua->account);
    echo "User ID: {$ua->user_id}, Account ID: {$ua->account} → " . ($acc ? $acc->name : 'UNKNOWN') . "\n";
}

// Test the correct query pattern
echo "\n=== Testing Correct Query Pattern ===\n";
$testAccountId = 2; // Main Store
$today = now()->startOfDay();
$todayEnd = now()->endOfDay();

$salesCount = salsModel::where('account', $testAccountId)
            ->whereBetween('created_at', [$today, $todayEnd])
            ->count();
$salesTotal = salsModel::where('account', $testAccountId)
            ->whereBetween('created_at', [$today, $todayEnd])
            ->sum('totalPrice');

echo "Query: salsModel::where('account', {$testAccountId})->whereBetween('today')->sum('totalPrice')\n";
echo "Result: Count = {$salesCount}, Total = {$salesTotal}\n";

if ($salesCount > 0 || $salesTotal > 0) {
    echo "\n✅ CORRECT: Using account ID (numeric) returns data!\n";
} else {
    echo "\n⚠️  No sales today for account {$testAccountId}. This may be normal.\n";
}

// Test the WRONG query pattern (what was happening before)
echo "\n=== Testing Wrong Query Pattern (account name) ===\n";
$testAccountName = 'Main Store';
$salesCountWrong = salsModel::where('account', $testAccountName)
                ->whereBetween('created_at', [$today, $todayEnd])
                ->count();
$salesTotalWrong = salsModel::where('account', $testAccountName)
                ->whereBetween('created_at', [$today, $todayEnd])
                ->sum('totalPrice');

echo "Query: salsModel::where('account', '{$testAccountName}')->whereBetween('today')->sum('totalPrice')\n";
echo "Result: Count = {$salesCountWrong}, Total = {$salesTotalWrong}\n";

if ($salesCountWrong == 0 && $salesTotalWrong == 0) {
    echo "\n❌ WRONG: Using account name returns 0 (this was the bug!)\n";
}

echo "\n=== Summary ===\n";
echo "The fix: Store account_id in session and use it in all queries.\n";
echo "Before: getSessionAccountName() returned name → queries returned 0\n";
echo "After: session('account_id') returns numeric ID → queries return actual data\n";