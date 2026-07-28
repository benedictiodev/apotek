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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('product_id');
            $table->foreignId('uom_id');
            $table->bigInteger('price');
            $table->integer('quantity');
            $table->float('discount');
            $table->bigInteger('total_price');
            $table->bigInteger('total_discount');
            $table->bigInteger('amount');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->index('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->index('uom_id');
            $table->foreign('uom_id')->references('id')->on('master_uom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
