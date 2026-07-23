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
        Schema::create('master_employee', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('company_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('master_employee', function (Blueprint $table) {
            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_employee');
    }
};
