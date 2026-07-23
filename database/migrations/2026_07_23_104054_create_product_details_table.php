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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uom_id');
            $table->bigInteger('price');
            $table->integer('contains');
            $table->float('discount')->default(0);
            $table->foreignId('product_id');
            $table->timestamps();
            $table->softDeletes();
        });

         Schema::table('product_details', function (Blueprint $table) {
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
        Schema::dropIfExists('product_details');
    }
};
