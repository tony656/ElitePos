<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$accountId = 1;
$Mstart_date = date('Y-m-01') . ' 00:00:00';
$Mend_date = date('Y-m-t') . ' 23:59:59';

echo "Testing return detection query:\n";
echo "================================\n";

$results = DB::select("
    SELECT
        DATE(created_at) as sale_date,
        SUM(totalPrice) as Msales,
        COALESCE(SUM(return_amount),0) as Mreturn,
        SUM(CASE WHEN return_amount = 0 OR return_amount IS NULL THEN paid ELSE 0 END) as Mcash_sales,
        SUM(CASE WHEN return_amount = 0 OR return_amount IS NULL THEN credit ELSE 0 END) as Mcredit_sales,
        COALESCE(SUM(CASE WHEN return_amount > 0 AND transactionType = 'Cash' THEN return_amount ELSE 0 END),0) as Mcash_returns,
        COALESCE(SUM(CASE WHEN return_amount > 0 AND transactionType = 'Credit' THEN return_amount ELSE 0 END),0) as Mcredit_returns
    FROM sales
    WHERE account = $accountId
      AND created_at BETWEEN '$Mstart_date' AND '$Mend_date'
      AND (salesName != '' OR salesName IS NULL)
    GROUP BY DATE(created_at)
    LIMIT 5
");

foreach($results as $r) {
    echo "Date: {$r->sale_date}\n";
    echo "  Total Sales: {$r->Msales}\n";
    echo "  Cash Sales: {$r->Mcash_sales}\n";
    echo "  Credit Sales: {$r->Mcredit_sales}\n";
    echo "  Cash Returns: {$r->Mcash_returns}\n";
    echo "  Credit Returns: {$r->Mcredit_returns}\n";
    echo "  Total Return Amount: {$r->Mreturn}\n";
    echo "\n";
}

echo "\nRaw return records:\n";
echo "===================\n";
$returns = DB::select("
    SELECT sales_id, status, transactionType, totalPrice, return_amount, paid, credit, created_at
    FROM sales 
    WHERE account = $accountId 
      AND return_amount > 0 
      AND created_at BETWEEN '$Mstart_date' AND '$Mend_date'
    LIMIT 10
");
foreach($returns as $r) {
    echo "ID: {$r->sales_id} | Status: {$r->status} | Type: {$r->transactionType} | Total: {$r->totalPrice} | Return: {$r->return_amount} | Paid: {$r->paid} | Credit: {$r->credit}\n";
}