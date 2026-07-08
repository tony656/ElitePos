<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$columns = DB::getSchemaBuilder()->getColumnListing('offers');
echo implode("\n", $columns);
