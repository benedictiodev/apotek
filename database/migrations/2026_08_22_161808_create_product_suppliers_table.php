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
        Schema::create('product_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id');
            $table->foreignId('product_id');
            $table->timestamps();
        });

        Schema::table('product_suppliers', function (Blueprint $table) {
            $table->index('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('master_suppliers');
            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_suppliers');
    }
};
