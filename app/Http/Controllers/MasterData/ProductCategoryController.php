<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    public function index(Request $request) {
        $data = MasterProductCategory::query()
            ->where("name", "like", "%$request->search%")
            ->paginate(10);
        return view('dashboard.master-data.product_category.index', [
            'data' => $data
        ]);
    }

    public function create() {
        return view('dashboard.master-data.product_category.create');
    }

    public function store(Request $request) {
        $validate = $request->validate([
            'name' => 'required',
        ]);

        $store = MasterProductCategory::create([
            'name' => $validate['name'],
            'company_id' => Auth::user()->company_id,
        ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.product-category')->with('success', "Berhasil menambahkan data kategori produk");
        } else {
            return redirect()->route('dashboard.master-data.product-category')->with('failed', "Gagal menambahkan data kategori produk");
        }
    }

    public function edit($id) {
        $data = MasterProductCategory::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();

        if ($data && $data->company_id == Auth::user()->company_id) {
            return view('dashboard.master-data.product_category.edit', ["data" => $data]);
        } else {
            return redirect()->route('dashboard.master-data.product-category')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function update(Request $request, $id) {
        $validate = $request->validate([
            'name' => 'required',
        ]);

        $store = MasterProductCategory::where('id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->update([
                'name' => $validate['name'],
            ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.product-category')->with('success', "Berhasil merubah data kategori produk");
        } else {
            return redirect()->route('dashboard.master-data.product-category')->with('failed', "Gagal merubah data kategori produk");
        }
    }

    public function destroy($id)
    {
        // $product = Product::find($id);
        $delete = MasterProductCategory::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->delete();

        if ($delete) {
            return redirect()->route('dashboard.master-data.product-category')->with('success', "Berhasil menghapus data kategori produk");
        } else {
            return redirect()->route('dashboard.master-data.product-category')->with('failed', "Gagal menghapus data kategori produk");
        }
    }
}
