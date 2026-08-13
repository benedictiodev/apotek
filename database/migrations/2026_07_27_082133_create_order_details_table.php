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
            $table->foreignId('product_detail_id');
            $table->string('uom');
            $table->integer('quantity_on_base_uom');
            $table->bigInteger('price');
            $table->integer('quantity');
            $table->bigInteger('total_price');
            $table->float('discount');
            $table->bigInteger('total_discount');
            $table->bigInteger('amount');
            $table->float('discount_order');
            $table->bigInteger('total_discount_order');
            $table->bigInteger('fix_amount');
            $table->bigInteger('base_price');
            $table->bigInteger('profit');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->index('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->index('product_detail_id');
            $table->foreign('product_detail_id')->references('id')->on('product_details');
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
