<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->json('offer_parent_products')->nullable()->after('offer_quantity');
        });

        $offers = DB::table('offers')->get();
        foreach ($offers as $offer) {
            if ($offer->product_id) {
                DB::table('offer_items')->insert([
                    'offer_id' => $offer->id,
                    'product_id' => $offer->product_id,
                    'required_quantity' => $offer->required_quantity,
                    'account' => $offer->account,
                    'created_at' => $offer->created_at,
                    'updated_at' => $offer->updated_at,
                ]);
            }
        }

        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['required_quantity']);
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->integer('required_quantity')->nullable()->after('product_id');
        });
    }
};
