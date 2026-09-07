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
        Schema::create('product_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_purchase_id');
            $table->foreignId('product_id');
            $table->foreignId('product_detail_id');
            $table->foreignId('product_stok_id');
            $table->string('uom');
            $table->integer('quantity_on_base_uom');
            $table->integer('quantity');
            $table->bigInteger('price');
            $table->bigInteger('amount');
            $table->timestamps();
        });

        Schema::table('product_purchase_details', function (Blueprint $table) {
            $table->index('product_purchase_id');
            $table->foreign('product_purchase_id')->references('id')->on('product_purchases');
            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->index('product_detail_id');
            $table->foreign('product_detail_id')->references('id')->on('product_details');
            $table->index('product_stok_id');
            $table->foreign('product_stok_id')->references('id')->on('product_stocks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_purchase_details');
    }
};
