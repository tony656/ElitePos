<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "=== Products Table Schema ===\n\n";

$columns = Schema::getColumnListing('products');
echo "Columns in products table:\n";
foreach ($columns as $col) {
    echo "  - {$col}\n";
}

echo "\n=== Checking actual data ===\n\n";
$products = \App\Models\productsModel::first();
if ($products) {
    echo "First product:\n";
    print_r($products->toArray());
} else {
    echo "No products in database\n";
}

echo "\n=== Testing save operation ===\n\n";
try {
    $test = new \App\Models\productsModel();
    $test->product_id = 'test-' . time();
    $test->name01 = 'Test Product';
    $test->quantity = 10;
    $test->bPrice = 1000;
    $test->sPrice = 1500;
    $test->account = 1;
    $test->save();
    echo "✓ Save succeeded\n";
    echo "  Product ID: " . $test->id . "\n";
    echo "  product_id: " . $test->product_id . "\n";
} catch (\Exception $e) {
    echo "❌ Save failed: " . $e->getMessage() . "\n";
}