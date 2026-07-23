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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('type_subscription');
            $table->integer('subscription_fee');
            $table->dateTime('expired_date');
            $table->dateTime('grace_days_ended_at');
            $table->text('settings_printer')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
