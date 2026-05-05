<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\UserAccount;
use App\Models\accountModel;
use Illuminate\Support\Facades\DB;

echo "=== Data Structure Check ===\n\n";

// Check user_accounts data
echo "--- user_accounts table ---\n";
$uas = UserAccount::all();
foreach ($uas as $ua) {
    echo "ID: {$ua->id}, user_id: {$ua->user_id}, account: '{$ua->account}' (type: " . gettype($ua->account) . ")\n";
}

// Check accounts data
echo "\n--- accounts table ---\n";
$accounts = accountModel::all();
foreach ($accounts as $acc) {
    echo "ID: {$acc->id}, name: '{$acc->name}'\n";
}

// Check what the relationship returns with current fix (belongsTo on name)
echo "\n=== Relationship Test (current fix: belongsTo on 'name') ===\n";
$first = UserAccount::first();
if ($first) {
    echo "First UA: account field = '{$first->account}'\n";
    $acc = $first->account; // Uses relationship
    if ($acc) {
        echo "  ✓ Got account: {$acc->name}\n";
    } else {
        echo "  ❌ Relationship returned null (no account with name '{$first->account}')\n";
    }
}

// Check what we need: mapping from ID to name
echo "\n=== ID to Name Mapping ===\n";
$idToName = [];
foreach ($accounts as $acc) {
    $idToName[$acc->id] = $acc->name;
}
print_r($idToName);

echo "\n=== UserAccount account values vs Accounts ===\n";
foreach ($uas as $ua) {
    $accId = (int)$ua->account;
    if (isset($idToName[$accId])) {
        echo "  UA account '{$ua->account}' → Account ID {$accId} → Name '{$idToName[$accId]}'\n";
    } else {
        echo "  ❌ UA account '{$ua->account}' has NO matching account ID!\n";
    }
}