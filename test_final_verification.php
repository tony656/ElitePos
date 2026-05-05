<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\UserAccount;
use App\Models\accountModel;
use App\Models\salsModel;

echo "=== Final Verification Test ===\n\n";

// Simulate a logged-in user's session
$user = Auth::user();
if (!$user) {
    echo "❌ No authenticated user. Please login first.\n";
    exit;
}

echo "✓ User: {$user->name} (ID: {$user->id}, Role: {$user->levelStatus})\n";

// Check session
$accountId = session('account_id');
$accountName = session('account');
echo "✓ Session account_id: " . ($accountId ?? 'NOT SET') . "\n";
echo "✓ Session account (name): " . ($accountName ?? 'NOT SET') . "\n";

// Get user's assigned accounts
$userAccounts = UserAccount::where('user_id', $user->id)->get();
echo "\n=== User's Account Assignments ===\n";
foreach ($userAccounts as $ua) {
    $acc = accountModel::find($ua->account);
    echo "  ID: {$ua->account} → " . ($acc ? $acc->name : 'UNKNOWN') . "\n";
}

// Test query with account ID
if ($accountId) {
    echo "\n=== Testing Sales Query with Account ID ===\n";
    $today = now()->startOfDay();
    $todayEnd = now()->endOfDay();
    
    $salesCount = salsModel::where('account', $accountId)
                ->whereBetween('created_at', [$today, $todayEnd])
                ->count();
    echo "✓ Sales today for account ID {$accountId}: {$salesCount}\n";
    
    $salesTotal = salsModel::where('account', $accountId)
                ->whereBetween('created_at', [$today, $todayEnd])
                ->sum('totalPrice');
    echo "✓ Sales total today: TSh " . number_format($salesTotal) . "\n";
    
    if ($salesCount > 0) {
        echo "\n✅ SUCCESS: Data is being fetched correctly!\n";
        echo "   The shop report should now show actual data instead of zeros.\n";
    } else {
        echo "\n⚠️  No sales found for today. This might be expected if no sales were made today.\n";
        echo "   Try selecting a different date in the shop report that has sales data.\n";
    }
} else {
    echo "\n❌ No account_id in session. Login required.\n";
}

echo "\n=== Test Complete ===\n";