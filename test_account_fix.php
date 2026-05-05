<?php
/**
 * Test script to verify account loading and report data fetching
 * Run this via browser or CLI to diagnose issues
 */

// Load Laravel framework
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\UserAccount;
use App\Models\accountModel;
use App\Models\salsModel;
use App\Models\expensesModel;
use App\Models\debtsModel;
use App\Models\recevingModel;
use App\Models\madeni;
use App\Models\cashSubmitModel;
use Illuminate\Support\Facades\DB;

echo "=== Account Loading Test ===\n\n";

// Test 1: Check if user is logged in
$user = Auth::user();
if (!$user) {
    echo "❌ No user logged in. Please login first.\n";
    exit;
}

echo "✓ Logged in as: {$user->name} (ID: {$user->id})\n";
echo "✓ User level: {$user->levelStatus}\n";

// Test 2: Check session account
$sessionAccount = session('account');
echo "✓ Session account: " . ($sessionAccount ?? 'NOT SET') . "\n";

// Test 3: Check user_accounts records
$userAccounts = UserAccount::where('user_id', $user->id)->get();
echo "\n=== User Account Assignments ===\n";
if ($userAccounts->isEmpty()) {
    echo "❌ No user_accounts records found for this user.\n";
} else {
    echo "✓ Found {$userAccounts->count()} account assignments:\n";
    foreach ($userAccounts as $ua) {
        echo "  - Account: '{$ua->account}' (Primary: " . ($ua->is_primary ? 'Yes' : 'No') . ")\n";
        
        // Test relationship
        $account = $ua->account;
        if ($account) {
            echo "    ✓ Linked to account: {$account->name} (ID: {$account->id})\n";
        } else {
            echo "    ❌ Relationship failed - no account found\n";
        }
    }
}

// Test 4: Check all accounts in database
$allAccounts = accountModel::all();
echo "\n=== All Accounts in Database ===\n";
foreach ($allAccounts as $acc) {
    echo "  ID: {$acc->id}, Name: '{$acc->name}', Location: '{$acc->location}'\n";
}

// Test 5: Test shop report query (AllShopReport logic)
echo "\n=== Testing Shop Report Query ===\n";
$dateParam = date('Y-m-d');
$reportDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateParam);
$start_date = $reportDate->copy()->startOfDay()->format('Y-m-d H:i:s');
$end_date = $reportDate->copy()->endOfDay()->format('Y-m-d H:i:s');

echo "Date range: {$start_date} to {$end_date}\n";

if ($user->levelStatus === 'Admin') {
    $allShops = accountModel::select('id', 'name', 'location')->orderBy('created_at', 'desc')->get();
    echo "✓ Admin: Loading all " . $allShops->count() . " shops\n";
} else {
    $assignedAccounts = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
    echo "✓ Assigned accounts (names): " . json_encode($assignedAccounts) . "\n";
    
    if (empty($assignedAccounts)) {
        echo "❌ No assigned accounts - shop report will be empty!\n";
        $allShops = collect();
    } else {
        // This is the FIXED query - using 'name' instead of 'id'
        $allShops = accountModel::whereIn('name', $assignedAccounts)
                    ->select('id', 'name', 'location')
                    ->orderBy('created_at', 'desc')
                    ->get();
        echo "✓ Found " . $allShops->count() . " shops for user\n";
    }
}

// Test 6: Check sales data for today
echo "\n=== Testing Sales Data ===\n";
$todaySales = salsModel::whereBetween('created_at', [$start_date, $end_date])->count();
echo "✓ Total sales records today (all accounts): {$todaySales}\n";

if ($sessionAccount) {
    $accountSales = salsModel::where('account', $sessionAccount)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->count();
    echo "✓ Sales for session account '{$sessionAccount}': {$accountSales}\n";
    
    if ($accountSales == 0) {
        echo "⚠️  No sales found for today for this account. This may be expected if no sales were made today.\n";
    }
}

// Test 7: Check if shop report will have data
echo "\n=== Shop Report Data Preview ===\n";
foreach ($allShops as $shop) {
    $shopSalesCount = salsModel::where('account', $shop->id)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->count();
    echo "  Shop '{$shop->id}': {$shopSalesCount} sales\n";
}

echo "\n=== Test Complete ===\n";
echo "If shops show 0 sales but you know there were sales today, check:\n";
echo "1. Session account matches the actual account name in sales records\n";
echo "2. Sales records have correct 'account' field values\n";
echo "3. User has correct account assignments in user_accounts table\n";