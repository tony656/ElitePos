<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $accountIds = DB::table('accounts')->pluck('id');

        foreach ($accountIds as $accountId) {
            $orderIds = DB::table('orders')
                ->where('account', $accountId)
                ->distinct()
                ->pluck('order_id');

            foreach ($orderIds as $orderId) {
                if (empty($orderId)) {
                    continue;
                }

                $rowIds = DB::table('orders')
                    ->where('account', $accountId)
                    ->where('order_id', $orderId)
                    ->orderBy('id')
                    ->pluck('id');

                if ($rowIds->count() <= 1) {
                    continue;
                }

                $keepId = $rowIds->first();
                $removeIds = $rowIds->slice(1);

                DB::table('orders')
                    ->where('account', $accountId)
                    ->whereIn('id', $removeIds)
                    ->update([
                        'paid' => 0,
                        'credit' => 0,
                    ]);
            }
        }
    }

    public function down(): void
    {
        // No rollback for data cleanup
    }
};
