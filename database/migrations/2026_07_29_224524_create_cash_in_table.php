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
        Schema::create('cash_in', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fund');
            $table->text('remarks')->nullable();
            $table->dateTime('date_time');
            $table->string('type');
            $table->foreignId('order_id')->nullable();
            $table->text('remarks_from_master')->nullable();
            $table->foreignId('company_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('cash_in', function (Blueprint $table) {
            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->index('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_in');
    }
};
