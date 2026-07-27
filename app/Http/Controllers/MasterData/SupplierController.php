<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index(Request $request) {
        $data = MasterSupplier::query()
            ->where('company_id', Auth::user()->company_id)
            ->where("name", "like", "%$request->search%")
            ->paginate(10);
        return view('dashboard.master-data.supplier.index', [
            'data' => $data
        ]);
    }

    public function create() {
        return view('dashboard.master-data.supplier.create');
    }

    public function store(Request $request) {
        $validate = $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $store = MasterSupplier::create([
            'code' => $validate['code'],
            'name' => $validate['name'],
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'bank_account_number' => $request->bank_account_number,
            'remarks' => $request->remarks,
            'company_id' => Auth::user()->company_id,
        ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.supplier')->with('success', "Berhasil menambahkan data supplier");
        } else {
            return redirect()->route('dashboard.master-data.supplier')->with('failed', "Gagal menambahkan data supplier");
        }
    }

    public function edit($id) {
        $data = MasterSupplier::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();

        if ($data && $data->company_id == Auth::user()->company_id) {
            return view('dashboard.master-data.supplier.edit', ["data" => $data]);
        } else {
            return redirect()->route('dashboard.master-data.supplier')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function update(Request $request, $id) {
        $validate = $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $store = MasterSupplier::where('id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->update([
                'code' => $validate['code'],
                'name' => $validate['name'],
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'bank_account_number' => $request->bank_account_number,
                'remarks' => $request->remarks,
                'company_id' => Auth::user()->company_id,
            ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.supplier')->with('success', "Berhasil merubah data supplier");
        } else {
            return redirect()->route('dashboard.master-data.supplier')->with('failed', "Gagal merubah data supplier");
        }
    }

    public function destroy($id)
    {
        // $product = Product::find($id);
        $delete = MasterSupplier::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->delete();

        if ($delete) {
            return redirect()->route('dashboard.master-data.supplier')->with('success', "Berhasil menghapus data supplier");
        } else {
            return redirect()->route('dashboard.master-data.supplier')->with('failed', "Gagal menghapus data supplier");
        }
    }
}
