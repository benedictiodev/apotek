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
        Schema::create('product_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice');
            $table->foreignId('supplier_id');
            $table->string('status');
            $table->date('date');
            $table->bigInteger('total_payment');
            $table->string('payment_method');
            $table->foreignId('company_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('product_purchases', function (Blueprint $table) {
            $table->index('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('master_suppliers');
            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_purchases');
    }
};
