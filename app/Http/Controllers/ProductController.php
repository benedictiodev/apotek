<?php

namespace App\Http\Controllers;

use App\Models\MasterProductCategory;
use App\Models\MasterSupplier;
use App\Models\MasterUom;
use App\Models\Product;
use App\Models\ProductDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request) {
        $data = Product::query()
            ->select('products.id', 'products.code', 'products.name', 'master_product_categories.name as category_name', 'products.stock', 'master_uom.name as uom_name', 'location')
            ->leftJoin('master_uom', 'products.base_uom_id', 'master_uom.id')
            ->leftJoin('master_product_categories', 'products.product_category_id', 'master_product_categories.id')
            ->where('products.company_id', Auth::user()->company_id)
            ->where("products.name", "like", "%$request->search%")
            ->paginate(10);
        return view('dashboard.product.index', [
            'data' => $data
        ]);
    }

    public function create() {
        
        $category = MasterProductCategory::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        $uom = MasterUom::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        $supplier = MasterSupplier::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        return view('dashboard.product.create', [
            'category' => $category,
            'uom' => $uom,
            'supplier' => $supplier
        ]);
    }

    public function store(Request $request) {
        try {
            $validate = $request->validate([
                'name' => 'required',
                'code' => 'required',
                'product_category_id' => 'required',
                'base_uom_id' => 'required',
                'purchase_price' => 'required',
                'stock' => 'required',
                'stock_minimal' => 'required',
                'expired_date' => 'required',
                'supplier_id' => 'required',
            ]);

            DB::beginTransaction();
            $store = Product::create([
                'code' => $validate['code'],
                'name' => $validate['name'],
                'product_category_id' => $validate['product_category_id'],
                'base_uom_id' => $validate['base_uom_id'],
                'purchase_price' => (int) str_replace('.', '', $validate['purchase_price']),
                'stock' => $validate['stock'],
                'stock_minimal' => $validate['stock_minimal'],
                'location' => $request->location ?? '',
                'expired_date' => $validate['expired_date'],
                'supplier_id' => $validate['supplier_id'],
                'company_id' => Auth::user()->company_id,
            ]);

            foreach ($request->uom_id as $key => $item) {
                $storeDetail = ProductDetail::create([
                    'uom_id' => $item,
                    'price' => (int) str_replace('.', '', $request->price[$key]),
                    'contains' => $request->contains[$key],
                    'discount' => $request->discount[$key] ?? 0,
                    'product_id' => $store->id,
                ]);
            }

            if ($store) {
                DB::commit();
                return redirect()->route('dashboard.product')->with('success', "Berhasil menambahkan data produk");
            } else {
                DB::rollBack();
                return redirect()->route('dashboard.product')->with('failed', "Gagal menambahkan data produk");
            }
        } catch (Exception $error) {
            dd($error->getMessage());
            DB::rollBack();
            return redirect()->route('dashboard.product')->with('failed', "Gagal menambahkan data produk");
        }
    }

    public function edit($id) {
        $data = Product::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();

        $dataDetail = ProductDetail::query()
            ->where('product_id', $data->id)
            ->get();

        $category = MasterProductCategory::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        $uom = MasterUom::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        $supplier = MasterSupplier::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        $uomId = $dataDetail[0]?->uom_id ?? null;
        $selectedUom = collect($uom)->firstWhere('id', $uomId);

        return view('dashboard.product.edit', [
            'selectedUom' => $selectedUom,
            'data' => $data,
            'dataDetail' => $dataDetail,
            'category' => $category,
            'uom' => $uom,
            'supplier' => $supplier
        ]);
    }

    public function update(Request $request, $id) {
        try {
            $validate = $request->validate([
                'name' => 'required',
                'code' => 'required',
                'product_category_id' => 'required',
                'base_uom_id' => 'required',
                'purchase_price' => 'required',
                'stock' => 'required',
                'stock_minimal' => 'required',
                'expired_date' => 'required',
                'supplier_id' => 'required',
            ]);

            DB::beginTransaction();
            $store = Product::where('id', $id)
                ->where('company_id', Auth::user()->company_id)
                ->update([
                    'code' => $validate['code'],
                    'name' => $validate['name'],
                    'product_category_id' => $validate['product_category_id'],
                    'base_uom_id' => $validate['base_uom_id'],
                    'purchase_price' => (int) str_replace('.', '', $validate['purchase_price']),
                    'stock' => $validate['stock'],
                    'stock_minimal' => $validate['stock_minimal'],
                    'location' => $request->location ?? '',
                    'expired_date' => $validate['expired_date'],
                    'supplier_id' => $validate['supplier_id'],
                    'company_id' => Auth::user()->company_id,
                ]);

            foreach ($request->uom_id as $key => $item) {
                if ($request->detail_id[$key]) {
                    ProductDetail::where('id', $request->detail_id[$key])
                        ->update([
                            'uom_id' => $item,
                            'price' => (int) str_replace('.', '', $request->price[$key]),
                            'contains' => $request->contains[$key],
                            'discount' => $request->discount[$key] ?? 0,
                        ]);
                } else {
                    $storeDetail = ProductDetail::create([
                        'uom_id' => $item,
                        'price' => (int) str_replace('.', '', $request->price[$key]),
                        'contains' => $request->contains[$key],
                        'discount' => $request->discount[$key] ?? 0,
                        'product_id' => $id,
                    ]);
                }
            }

            if ($store) {
                DB::commit();
                return redirect()->route('dashboard.product')->with('success', "Berhasil memperbarui data produk");
            } else {
                DB::rollBack();
                return redirect()->route('dashboard.product')->with('failed', "Gagal memperbarui data produk");
            }
        } catch (Exception $error) {
            throw $error;
            DB::rollBack();
            return redirect()->route('dashboard.product')->with('failed', "Gagal memperbarui data produk");
        }
    }

    public function destroy($id) {
        $delete = Product::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->delete();

        if ($delete) {
            return redirect()->route('dashboard.product')->with('success', "Berhasil menghapus data produk");
        } else {
            return redirect()->route('dashboard.product')->with('failed', "Gagal menghapus data produk");
        }
    }
}
