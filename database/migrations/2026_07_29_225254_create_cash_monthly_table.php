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
        Schema::create('cash_monthly', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('debit')->default(0);
            $table->bigInteger('kredit')->default(0);
            $table->bigInteger('amount')->default(0);
            $table->bigInteger('total_amount')->default(0);
            $table->date('date');
            $table->foreignId('company_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('cash_monthly', function (Blueprint $table) {
            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_monthly');
    }
};
