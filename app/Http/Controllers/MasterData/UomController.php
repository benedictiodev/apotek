<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterUom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UomController extends Controller
{
    public function index(Request $request) {
        $data = MasterUom::query()
            ->where('company_id', Auth::user()->company_id)
            ->where("name", "like", "%$request->search%")
            ->paginate(10);
        return view('dashboard.master-data.uom.index', [
            'data' => $data
        ]);
    }

    public function create() {
        return view('dashboard.master-data.uom.create');
    }

    public function store(Request $request) {
        $validate = $request->validate([
            'name' => 'required',
        ]);

        $store = MasterUom::create([
            'name' => $validate['name'],
            'company_id' => Auth::user()->company_id,
        ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.uom')->with('success', "Berhasil menambahkan data satuan produk");
        } else {
            return redirect()->route('dashboard.master-data.uom')->with('failed', "Gagal menambahkan data satuan produk");
        }
    }

    public function edit($id) {
        $data = MasterUom::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();

        if ($data && $data->company_id == Auth::user()->company_id) {
            return view('dashboard.master-data.uom.edit', ["data" => $data]);
        } else {
            return redirect()->route('dashboard.master-data.uom')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function update(Request $request, $id) {
        $validate = $request->validate([
            'name' => 'required',
        ]);

        $store = MasterUom::where('id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->update([
                'name' => $validate['name'],
            ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.uom')->with('success', "Berhasil merubah data satuan produk");
        } else {
            return redirect()->route('dashboard.master-data.uom')->with('failed', "Gagal merubah data satuan produk");
        }
    }

    public function destroy($id)
    {
        // $product = Product::find($id);
        $delete = MasterUom::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->delete();

        if ($delete) {
            return redirect()->route('dashboard.master-data.uom')->with('success', "Berhasil menghapus data satuan produk");
        } else {
            return redirect()->route('dashboard.master-data.uom')->with('failed', "Gagal menghapus data satuan produk");
        }
    }
}
