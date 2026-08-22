<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\MasterProductCategory;
use App\Models\MasterSupplier;
use App\Models\MasterUom;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('app:import-db-product-csv')]
#[Description('Command description')]
class ImportDbProductCsv extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = storage_path('app/import/dbarang.csv');

        if (! file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");

            return self::FAILURE;
        }

        $companies = Company::get();

        if ($companies->isEmpty()) {
            $this->error('Company tidak ditemukan.');
            return self::FAILURE;
        }

        $companyOptions = $companies->mapWithKeys(function ($company) {
            return [
                $company->id => $company->name,
            ];
        })->toArray();

        $companySelected = $this->choice(
            'Pilih Company',
            $companyOptions
        );

        $companyId = Company::where('name', $companySelected)->value('id');
        $this->info("Company Name yang dipilih: {$companySelected}");
        $this->info("Company ID yang dipilih: {$companyId}");

        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            $this->error('File tidak dapat dibuka.');

            return self::FAILURE;
        }

        // Row pertama sebagai header
        $header = fgetcsv($handle, 0, ',');

        if ($header === false) {
            fclose($handle);

            $this->error('File CSV kosong.');

            return self::FAILURE;
        }

        $rowNumber = 1;

        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            $rowNumber++;

            $row = array_combine($header, $data);

            if ($row === false) {
                $this->warn("Format row {$rowNumber} tidak sesuai dengan header.");

                continue;
            }

            // Contoh
            $this->info("Processing row {$rowNumber}");

            $masterProductCategoryItem = MasterProductCategory::where('name', $row['kategori'])
                ->where('company_id', $companyId)
                ->first();
            if (!$masterProductCategoryItem) {
                $masterProductCategoryItem = MasterProductCategory::create([
                    'name' => $row['kategori'],
                    'company_id' => $companyId,
                ]);
            }

            $masterBaseUomIten = MasterUom::where('name', $row['satuan_dasar'])
                ->where('company_id', $companyId)
                ->first();
            if (!$masterBaseUomIten) {
                $masterBaseUomIten = MasterUom::create([
                    'name' => $row['satuan_dasar'],
                    'company_id' => $companyId,
                ]);
            }

            $supplierId = null;
            if ($row['suplier'] && ($row['suplier'] != '' || $row['suplier'] != '-')) {
                $masterSupplierItem = MasterSupplier::where('name', $row['suplier'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$masterSupplierItem) {
                    $masterSupplierItem = MasterSupplier::create([
                        'code' => $row['suplier'],
                        'name' => $row['suplier'],
                        'company_id' => $companyId,
                    ]);
                }
                $supplierId = $masterSupplierItem->id;
            }

            $storeProduct = Product::create([
                'code' => $row['kode'],
                'name' => $row['nama'],
                'product_category_id' => $masterProductCategoryItem->id,
                'base_uom_id' => $masterBaseUomIten->id,
                'purchase_price' => (int) str_replace('.', '', $row['harga_beli']),
                'stock' => $row['stok'],
                'stock_minimal' => ($row['stok_min'] && $row['stok_min'] != '-' && $row['stok_min'] != '0' && $row['stok_min'] != '' ? $row['stok_min'] : 0),
                'location' => $row->lokasi ?? '',
                'expired_date' => ($row['expired'] && $row['expired'] != '-' && $row['expired'] != '0' && $row['expired'] != '') ? $row['expired'] : null,
                'supplier_id' => $supplierId,
                'company_id' => $companyId,
            ]);

            $satuan = [1, 2, 3];
            foreach ($satuan as $item) {
                if ($row['satuan_' . $item] && ($row['satuan_' . $item] != '' && $row['satuan_' . $item] != '-')) {
                    $masterUomIten = MasterUom::where('name', $row['satuan_' . $item])
                        ->where('company_id', $companyId)
                        ->first();
                    if (!$masterUomIten) {
                        $masterUomIten = MasterUom::create([
                            'name' => $row['satuan_dasar'],
                            'company_id' => $companyId,
                        ]);
                    }
                    $storeDetail = ProductDetail::create([
                        'uom_id' => $masterUomIten->id,
                        'price' => (int) str_replace('.', '', $row['harga_jual_' . $item]),
                        'contains' => $row['isi_' . $item],
                        'discount' => $row['diskon_' . $item] ?? 0,
                        'product_id' => $storeProduct->id,
                    ]);
                }
            }
        }

        fclose($handle);

        $this->info("Selesai membaca CSV.");

        return self::SUCCESS;
    }
}
