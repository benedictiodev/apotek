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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable();
            $table->string('username');
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->softDeletes();

            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('username');
            $table->dropColumn('address');
            $table->dropColumn('phone_number');
            $table->dropSoftDeletes();
        });
    }
};
