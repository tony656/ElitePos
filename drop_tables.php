<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = ['transaction_discrepancies', 'transaction_balances', 'transactions'];
foreach ($tables as $table) {
    try {
        DB::statement("DROP TABLE IF EXISTS $table");
        echo "Dropped table: $table\n";
    } catch (Exception $e) {
        echo "Error dropping $table: " . $e->getMessage() . "\n";
    }
}
echo "Done.\n";