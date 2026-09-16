<?php

namespace App\Http\Controllers;

use App\Models\CashMonthly;
use App\Models\CashOut;
use App\Models\MasterPaymentMethod;
use App\Models\MasterProductCategory;
use App\Models\MasterSupplier;
use App\Models\MasterUom;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductPurchase;
use App\Models\ProductPurchaseDetail;
use App\Models\ProductStock;
use App\Models\ProductSupplier;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request) {
        $data = Product::query()
            ->select('products.id', 'products.code', 'products.name', 'master_product_categories.name as category_name', 'master_uom.name as uom_name', 'location')
            ->leftJoin('master_uom', 'products.base_uom_id', 'master_uom.id')
            ->leftJoin('master_product_categories', 'products.product_category_id', 'master_product_categories.id')
            ->where('products.company_id', Auth::user()->company_id)
            ->where("products.name", "like", "%$request->search%")
            ->paginate(10);

        $productIds = $data->pluck('id');

        $stocks = ProductStock::query()
            ->select(
                'product_id',
                DB::raw('SUM(stock) as total_stock')
            )
            ->whereIn('product_id', $productIds)
            ->groupBy('product_id')
            ->pluck('total_stock', 'product_id');

        $data->getCollection()->transform(function ($product) use ($stocks) {
            $product->stock = $stocks[$product->id] ?? 0;

            return $product;
        });

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
            // dd($request);

            DB::beginTransaction();
            $store = Product::create([
                'code' => $validate['code'],
                'name' => $validate['name'],
                'product_category_id' => $validate['product_category_id'],
                'base_uom_id' => $validate['base_uom_id'],
                'purchase_price' => (int) str_replace('.', '', $validate['purchase_price']),
                // 'stock' => $validate['stock'],
                'stock_minimal' => $validate['stock_minimal'],
                'location' => $request->location ?? '',
                // 'expired_date' => $validate['expired_date'],
                // 'supplier_id' => $validate['supplier_id'],
                'company_id' => Auth::user()->company_id,
            ]);

            ProductSupplier::create([
                'supplier_id' => $validate['supplier_id'],
                'product_id' => $store->id,
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

            foreach ($request->batch as $key => $item) {
                if ($item) {
                    $storeStock = ProductStock::create([
                        'batch' => $item,
                        'stock' => $request->stock[$key] ?? 0,
                        'expired_date' => $request->expired_date[$key],
                        'product_id' => $store->id,
                    ]);
                }
            }

            if ($store) {
                DB::commit();
                return redirect()->route('dashboard.product.master')->with('success', "Berhasil menambahkan data produk");
            } else {
                DB::rollBack();
                return redirect()->route('dashboard.product.master')->with('failed', "Gagal menambahkan data produk");
            }
        } catch (Exception $error) {
            DB::rollBack();
            return redirect()->route('dashboard.product.master')->with('failed', "Gagal menambahkan data produk");
        }
    }

    public function store_api(Request $request) {
        try {
            $validate = $request->validate([
                'name' => 'required',
                'code' => 'required',
                'product_category_id' => 'required',
                'base_uom_id' => 'required',
                'purchase_price' => 'required',
                'stock_minimal' => 'required',
            ]);
            // dd($request);

            DB::beginTransaction();
            $store = Product::create([
                'code' => $validate['code'],
                'name' => $validate['name'],
                'product_category_id' => $validate['product_category_id'],
                'base_uom_id' => $validate['base_uom_id'],
                'purchase_price' => (int) str_replace('.', '', $validate['purchase_price']),
                // 'stock' => $validate['stock'],
                'stock_minimal' => $validate['stock_minimal'],
                'location' => $request->location ?? '',
                // 'expired_date' => $validate['expired_date'],
                // 'supplier_id' => $validate['supplier_id'],
                'company_id' => Auth::user()->company_id,
            ]);

            // ProductSupplier::create([
            //     'supplier_id' => $validate['supplier_id'],
            //     'product_id' => $store->id,
            // ]);

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
                $data = Product::query()
                    ->select('products.id', 'products.code', 'products.name')
                    ->with(['ProductDetail', 'ProductDetail.Uom', 'Stock' => function($query) {
                        $query->where('stock', '>', 0);
                    }])
                    ->where('products.id', $store->id)
                    ->get();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan',
                    'data' => $data,
                ], 201);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan produk',
                ], 400);
            }
        } catch (ValidationException $error) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $error->errors(),
            ], 422);
        }
    }

    public function show($id) {
        $data = Product::query()
            ->with(['Category', 'BaseUom'])
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();

        $dataDetail = ProductDetail::query()
            ->with(['Uom'])
            ->where('product_id', $data->id)
            ->get();

        $totalStock = ProductStock::query()
            ->selectRaw('Sum(stock) as stocks')
            ->where('product_id', $data->id)
            ->value('stocks');

        $stockDetail = ProductStock::query()
            ->where('product_id', $data->id)
            ->where('stock', '>', 0)
            ->get();

        $supplierDetail = ProductSupplier::query()
            ->with(['Supplier'])
            ->where('product_id', $data->id)
            ->get();

        return view('dashboard.product.detail', [
            'data' => $data,
            'totalStock' => $totalStock,
            'dataDetail' => $dataDetail,
            'stockDetail' => $stockDetail,
            'supplierDetail' => $supplierDetail,
        ]);
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
                'stock_minimal' => 'required',
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
                    'stock_minimal' => $validate['stock_minimal'],
                    'location' => $request->location ?? '',
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
                return redirect()->route('dashboard.product.master.detail', ['id' => $id])->with('success', "Berhasil memperbarui data produk");
            } else {
                DB::rollBack();
                return redirect()->route('dashboard.product.master')->with('failed', "Gagal memperbarui data produk");
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
            return redirect()->route('dashboard.product.master')->with('success', "Berhasil menghapus data produk");
        } else {
            return redirect()->route('dashboard.product.master')->with('failed', "Gagal menghapus data produk");
        }
    }

    public function search(Request $request) {
        $data = Product::query()
            ->select('products.id', 'products.code', 'products.name')
            ->with(['ProductDetail', 'ProductDetail.Uom', 'Stock' => function($query) {
                $query->where('stock', '>', 0);
            }])
            ->where('products.company_id', Auth::user()->company_id)
            ->where(function($querySearch) use($request) {
                $querySearch->where("products.name", "like", "%$request->search%")
                    ->orWhere("products.code", "like", "%$request->search%");
            })
            ->get();
        
        return response()->json([
            'data' => json_encode($data)
        ]);
    }

    public function indexPurchase(Request $request) {
        $data = ProductPurchase::query()
            ->with(['Supplier', 'PurchaseDetail'])
            ->where('company_id', Auth::user()->company_id)
            ->paginate(10);

        $supplier = MasterSupplier::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        return view('dashboard.product.purchase.index', [
            "data" => $data,
            "supplier" => $supplier
        ]);
    }

    public function StorePurchase(Request $request) {
        try {
            $validate = $request->validate([
                'no_invoice' => 'required',
                'supplier_id' => 'required',
                'date' => 'required',
            ]);

            $store = ProductPurchase::create([
                "no_invoice" => $validate['no_invoice'],
                "supplier_id" => $validate['supplier_id'],
                "status" => 'Draf',
                "date" => $validate['date'],
                "total_payment" => 0,
                "payment_method" => "",
                "company_id" => Auth::user()->company_id,
            ]);

            return redirect()->route('dashboard.product.purchase.show', ['id' => $store->id])->with('success', "Berhasil menambahkan data pembelian produk");
        } catch (Exception $error) {
            return redirect()->route('dashboard.product.purchase')->with('failed', "Gagal menambahkan data pembelian produk");
        }
    }

    public function ShowPurchase($id) {
        $data = ProductPurchase::where('id', $id)
            ->with(['Supplier', 'PurchaseDetail', 'PurchaseDetail.Product', 'PurchaseDetail.Product.ProductDetail', 'PurchaseDetail.Product.ProductDetail.Uom', 'PurchaseDetail.Stock'])
            ->where('company_id', Auth::user()->company_id)
            ->first();

        $paymentMethod = MasterPaymentMethod::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        $category = MasterProductCategory::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        $uom = MasterUom::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        return view('dashboard.product.purchase.edit', [
            "data" => $data,
            "paymentMethod" => $paymentMethod,
            "uom" => $uom,
            "category" => $category,
        ]);
    }

    public function StoreDetailPurchase(Request $request, $id) {
        try {
            DB::beginTransaction();
            $productPurchase = ProductPurchase::where('id', $id)->first();
            ProductPurchase::where('id', $id)->update([
                "total_payment" => $productPurchase->total_payment + ((int) str_replace('.', '', $request['amount-add'])),
            ]);

            $productDetail = ProductDetail::with('Uom')->where('id', $request['uom_id-add'])->first();

            $productStock = ProductStock::where('product_id', $request['product_id-add'])
                ->where('batch', $request['batch-add'])
                ->first();
            if (!$productStock) {
                $productStock = ProductStock::create([
                    'batch' => $request['batch-add'],
                    'stock' => 0,
                    'expired_date' => $request['expired_date-add'],
                    'product_id' => $request['product_id-add'],
                ]);
            }

            ProductPurchaseDetail::create([
                "product_purchase_id" => $id,
                "product_id" => $request['product_id-add'],
                "product_detail_id" => $request['uom_id-add'],
                "product_stok_id" => $productStock->id,
                "uom" => $productDetail->Uom->name,
                "quantity_on_base_uom" => $productDetail->contains * $request['quantity-add'],
                "quantity" => $request['quantity-add'],
                "price" => ((int) str_replace('.', '', $request['price-add'])),
                "amount" => ((int) str_replace('.', '', $request['amount-add'])),
            ]);

            DB::commit();
            return redirect()->route('dashboard.product.purchase.show', ['id' => $id])->with('success', "Berhasil menambahkan data pembelian produk");
        } catch (Exception $error) {
            DB::rollBack();
            return redirect()->route('dashboard.product.purchase.show', ['id' => $id])->with('failed', "Gagal menambahkan data pembelian produk");
        }
    }

    public function UpdateDetailPurchase(Request $request) {
        $data = ProductPurchaseDetail::where('id', $request->detail_id)->first();
        try {
            DB::beginTransaction();
            $productPurchase = ProductPurchase::where('id', $data->product_purchase_id)->first();
            ProductPurchase::where('id', $data->product_purchase_id)->update([
                "total_payment" => $productPurchase->total_payment - $data->amount + ((int) str_replace('.', '', $request->amount)),
            ]);

            $productDetail = ProductDetail::with('Uom')->where('id', $request->uom_id)->first();

            $productStock = ProductStock::where('product_id', $data->product_id)
                ->where('batch', $request->batch)
                ->first();
            if (!$productStock) {
                $productStock = ProductStock::create([
                    'batch' => $request->batch,
                    'stock' => 0,
                    'expired_date' => $request->expired_date,
                    'product_id' => $data->product_id,
                ]);
            }

            ProductPurchaseDetail::where('id', $request->detail_id)->update([
                "product_detail_id" => $request->uom_id,
                "product_stok_id" => $productStock->id,
                "uom" => $productDetail->Uom->name,
                "quantity_on_base_uom" => $productDetail->contains * $request->quantity,
                "quantity" => $request->quantity,
                "price" => ((int) str_replace('.', '', $request->price)),
                "amount" => ((int) str_replace('.', '', $request->amount)),
            ]);

            DB::commit();
            return redirect()->route('dashboard.product.purchase.show', ['id' => $data->product_purchase_id])->with('success', "Berhasil merubah data pembelian produk");
        } catch (Exception $error) {
            DB::rollBack();
            return redirect()->route('dashboard.product.purchase.show', ['id' => $data->product_purchase_id])->with('failed', "Gagal merubah data pembelian produk");
        }
    }

    public function DeleteDetailPurchase($id) {
        $data = ProductPurchaseDetail::where('id', $id)->first();
        try {
            DB::beginTransaction();
            $productPurchase = ProductPurchase::where('id', $data->product_purchase_id)->first();
            ProductPurchase::where('id', $data->product_purchase_id)->update([
                "total_payment" => $productPurchase->total_payment - $data->amount,
            ]);

            ProductPurchaseDetail::where('id', $id)->delete();

            DB::commit();
            return redirect()->route('dashboard.product.purchase.show', ['id' => $data->product_purchase_id])->with('success', "Berhasil menghapus data pembelian produk");
        } catch (Exception $error) {
            DB::rollBack();
            return redirect()->route('dashboard.product.purchase.show', ['id' => $data->product_purchase_id])->with('failed', "Gagal menghapus data pembelian produk");
        }
    }

    public function confirmationPurchase(Request $request, $id) {
        try {
            DB::beginTransaction();
            $productPurchase = ProductPurchase::where('id', $id)->first();
            ProductPurchase::where('id', $id)->update([
                "payment_method" => $request->payment_method,
                "status" => "Done",
            ]);

            $purchaseDetail = ProductPurchaseDetail::where('product_purchase_id', $id)->get();
            foreach ($purchaseDetail as $item) {
                $productStock = ProductStock::where('id', $item->product_stok_id)->first();
                ProductStock::where('id', $item->product_stok_id)->update([
                    'stock' => $productStock->stock + $item->quantity_on_base_uom
                ]);
            }

            CashOut::create([
                'company_id' => Auth::user()->company_id,
                'fund' => $productPurchase->total_payment,
                'remark' => null,
                'date_time' => Carbon::now()->toDateTimeString(),
                'type' => $request->payment_method,
                'purchase_id' => $id,
                'remarks_from_master' => null,
            ]);

            $cash_monthly = CashMonthly::where("company_id", Auth::user()->company_id)
                ->where("date", Carbon::now()->toDateString())->first();
            if ($cash_monthly) {
                CashMonthly::where("id", $cash_monthly->id)->update([
                    "debit" => (int) $cash_monthly->debit + $productPurchase->total_payment,
                    "amount" => (int) $cash_monthly->amount - $productPurchase->total_payment,
                    "total_amount" => (int) $cash_monthly->total_amount - $productPurchase->total_payment,
                ]);
            } else {
                CashMonthly::create([
                    "company_id" => Auth::user()->company_id,
                    "debit" => $productPurchase->total_payment,
                    "kredit" => 0,
                    "amount" => 0 - $productPurchase->total_payment,
                    "total_amount" => 0 - $productPurchase->total_payment,
                    "date" => Carbon::now()->toDateString()
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.product.purchase.show', ['id' => $id])->with('success', "Berhasil konfirmasi pembelian produk");
        } catch (Exception $error) {
            DB::rollBack();
            return redirect()->route('dashboard.product.purchase.show', ['id' => $id])->with('failed', "Gagal konfirmasi pembelian produk");
        }
    }
}
