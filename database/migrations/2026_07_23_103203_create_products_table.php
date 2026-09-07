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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->foreignId('product_category_id');
            // $table->integer('stock');
            $table->integer('stock_minimal');
            $table->foreignId('base_uom_id');
            $table->bigInteger('purchase_price');
            // $table->foreignId('supplier_id');
            $table->string('location');
            // $table->date('expired_date');
            $table->foreignId('company_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->index('product_category_id');
            $table->foreign('product_category_id')->references('id')->on('master_product_categories');
            $table->index('base_uom_id');
            $table->foreign('base_uom_id')->references('id')->on('master_uom');
            // $table->index('supplier_id');
            // $table->foreign('supplier_id')->references('id')->on('master_suppliers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
