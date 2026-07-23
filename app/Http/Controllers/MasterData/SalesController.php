<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index(Request $request) {
        $data = MasterSales::query()
            ->where("name", "like", "%$request->search%")
            ->paginate(10);
        return view('dashboard.master-data.sales.index', [
            'data' => $data
        ]);
    }

    public function create() {
        return view('dashboard.master-data.sales.create');
    }

    public function store(Request $request) {
        $validate = $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $store = MasterSales::create([
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
            return redirect()->route('dashboard.master-data.sales')->with('success', "Berhasil menambahkan data sales");
        } else {
            return redirect()->route('dashboard.master-data.sales')->with('failed', "Gagal menambahkan data sales");
        }
    }

    public function edit($id) {
        $data = MasterSales::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();

        if ($data && $data->company_id == Auth::user()->company_id) {
            return view('dashboard.master-data.sales.edit', ["data" => $data]);
        } else {
            return redirect()->route('dashboard.master-data.sales')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function update(Request $request, $id) {
        $validate = $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $store = MasterSales::where('id', $id)
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
            return redirect()->route('dashboard.master-data.sales')->with('success', "Berhasil merubah data sales");
        } else {
            return redirect()->route('dashboard.master-data.sales')->with('failed', "Gagal merubah data sales");
        }
    }

    public function destroy($id)
    {
        // $product = Product::find($id);
        $delete = MasterSales::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->delete();

        if ($delete) {
            return redirect()->route('dashboard.master-data.sales')->with('success', "Berhasil menghapus data sales");
        } else {
            return redirect()->route('dashboard.master-data.sales')->with('failed', "Gagal menghapus data sales");
        }
    }
}
