<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->foreignId('product_stok_id')->nullable()->after('product_detail_id');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->index('product_stok_id');
            $table->foreign('product_stok_id')->references('id')->on('product_stocks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['product_stok_id']);
            $table->dropIndex(['product_stok_id']);
            $table->dropColumn('product_stok_id');
        });
    }
};
