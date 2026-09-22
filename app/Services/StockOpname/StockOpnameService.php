<?php

namespace App\Services\StockOpname;

use App\Models\StockOpname;
use App\Models\ProductStock;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class StockOpnameService
{
    /**
     * Memproses penyimpanan Draft maupun Complete dalam satu transaksi.
     */
    public function processOpname(StockOpname $opname, array $items, string $action)
    {
        // DB::transaction menjamin: jika salah satu query gagal, semua query dibatalkan (rollback)
        return DB::transaction(function () use ($opname, $items, $action) {

            // 1. Reset detail lama, ganti dengan data dari keranjang (UI) terbaru
            $opname->details()->delete();

            $detailsData = array_map(function ($item) {
                return [
                    'product_id' => $item['product_id'],
                    'product_stock_id' => $item['product_stock_id'],
                    'system_stock' => $item['system_stock'],
                    'physical_stock' => $item['physical_stock'],
                    'discrepancy' => $item['discrepancy'],
                    'reason' => $item['reason'] ?? null,
                ];
            }, $items);

            $opname->details()->createMany($detailsData);

            // 2. Cabang Logika berdasarkan Aksi
            if ($action === 'COMPLETE') {
                $this->executeCompletion($opname);
            } else {
                $opname->update(['status' => 'DRAFT']);
            }

            return $opname;
        });
    }

    /**
     * Mengeksekusi penyesuaian stok saat status COMPLETE.
     * Dipanggil secara private dari dalam blok DB::transaction di atas.
     */
    private function executeCompletion(StockOpname $opname)
    {
        if (!in_array($opname->status, ['NEW', 'DRAFT'])) {
            throw new Exception("Status opname tidak valid untuk diselesaikan.");
        }

        // Loop detail yang baru saja disimpan
        foreach ($opname->details as $detail) {
            if ($detail->discrepancy != 0) {

                // lockForUpdate() Mencegah transaksi kasir mengubah stok obat ini saat proses opname sedang berjalan
                $batch = ProductStock::lockForUpdate()->find($detail->product_stock_id);

                if ($batch) {
                    // Update stok fisik di tabel master batch
                    $batch->update(['stock' => $detail->physical_stock]);

                    // Catat ke buku besar mutasi (Audit Trail)
                    StockLog::create([
                        'product_id' => $detail->product_id,
                        'product_stock_id' => $batch->id,
                        'user_id' => Auth::id(),
                        'type' => 'OPNAME',
                        'qty_change' => $detail->discrepancy,
                        'balance_after' => $detail->physical_stock, // Saldo akhir langsung dikunci
                        'reference_type' => StockOpname::class,
                        'reference_id' => $opname->id,
                        'note' => 'Penyesuaian Opname: ' . $detail->reason
                    ]);
                }
            }
        }

        // Kunci status Opname
        $opname->update([
            'status' => 'DONE',
            'completed_at' => now()
        ]);
    }
}
