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
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_stock_id')->constrained('product_stocks');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->enum('type', [
                'SALE',
                'PURCHASE',
                'OPNAME',
                'MANUAL_ADJUSTMENT'
            ]);
            $table->integer('qty_change');
            $table->integer('balance_after');

            // POLYMORPHIC RELATIONS
            // Akan otomatis membuat 2 kolom: `reference_type` (string) & `reference_id` (bigint) + Index
            // Contoh isi reference_type: 'App\Models\StockOpname' atau 'App\Models\Sale'
            $table->nullableMorphs('reference');
            $table->timestamps();

            // Index tambahan untuk optimasi query laporan kartu stok
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
