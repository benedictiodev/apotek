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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('id_order');
            $table->integer('sequence');
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('cashier_id');
            $table->dateTime('date_time');
            $table->bigInteger('total_price_item');
            $table->float('discount');
            $table->bigInteger('total_discount');
            $table->bigInteger('total_payment');
            $table->bigInteger('payment');
            $table->bigInteger('change');
            $table->string('payment_method');
            $table->string('status');
            $table->text('remarks');
            $table->foreignId('company_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->index('customer_id');
            $table->foreign('customer_id')->references('id')->on('master_customers');
            $table->index('cashier_id');
            $table->foreign('cashier_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
