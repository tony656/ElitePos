<?php
/**
 * Test script to verify UserAccount relationship fix
 * This tests the database relationships without requiring login
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\UserAccount;
use App\Models\accountModel;
use Illuminate\Support\Facades\DB;

echo "=== UserAccount Relationship Test ===\n\n";

// Test 1: Check all user_accounts records
$userAccounts = UserAccount::with('account')->get();
echo "Total user_accounts records: " . $userAccounts->count() . "\n\n";

if ($userAccounts->isEmpty()) {
    echo "❌ No user_accounts records found.\n";
    exit;
}

$hasErrors = false;
$hasSuccess = false;

foreach ($userAccounts as $ua) {
    echo "User ID: {$ua->user_id}, Account field: '{$ua->account}'\n";
    
    if ($ua->account) {
        $account = $ua->account; // This uses the relationship
        
        if ($account) {
            echo "  ✓ Relationship OK: Found account '{$account->name}' (ID: {$account->id})\n";
            $hasSuccess = true;
        } else {
            echo "  ❌ Relationship FAILED: No account found for '{$ua->account}'\n";
            $hasErrors = true;
        }
    } else {
        echo "  ⚠️  Account field is null\n";
    }
    echo "\n";
}

// Test 2: Check all accounts
echo "\n=== All Accounts ===\n";
$accounts = accountModel::all();
foreach ($accounts as $acc) {
    echo "  ID: {$acc->id}, Name: '{$acc->name}'\n";
}

// Test 3: Verify the relationship query works
echo "\n=== Relationship Query Test ===\n";
$firstUA = UserAccount::first();
if ($firstUA) {
    echo "First user_account record: user_id={$firstUA->user_id}, account='{$firstUA->account}'\n";
    $account = $firstUA->account;
    if ($account) {
        echo "✓ Relationship resolved: {$account->name} (ID: {$account->id})\n";
    } else {
        echo "❌ Relationship returned null!\n";
        echo "   This means the 'account' field value '{$firstUA->account}' doesn't match any account's 'name' field.\n";
    }
}

// Test 4: Check if any user_accounts.account values don't match accounts.name
echo "\n=== Data Consistency Check ===\n";
$uaAccountNames = UserAccount::distinct()->pluck('account')->toArray();
$accountNames = accountModel::pluck('name')->toArray();

$missing = array_diff($uaAccountNames, $accountNames);
if (!empty($missing)) {
    echo "❌ These account names in user_accounts have no matching account:\n";
    foreach ($missing as $name) {
        echo "   - '{$name}'\n";
    }
    $hasErrors = true;
} else {
    echo "✓ All user_accounts.account values match accounts.name\n";
}

echo "\n=== Summary ===\n";
if ($hasErrors) {
    echo "❌ There are relationship errors that will cause reports to show 0.\n";
    echo "   Fix: Update user_accounts.account to reference valid accounts.name values.\n";
} else {
    echo "✓ Relationships are working correctly.\n";
    if ($hasSuccess) {
        echo "✓ The fix (changing belongsTo to match on 'name') is correct.\n";
    }
}