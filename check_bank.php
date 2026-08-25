<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$cols = DB::getSchemaBuilder()->getColumnListing('sales');
echo in_array('bank', $cols) ? "bank exists in sales\n" : "bank NOT in sales\n";

$cols2 = DB::getSchemaBuilder()->getColumnListing('orders');
echo in_array('bank', $cols2) ? "bank exists in orders\n" : "bank NOT in orders\n";
