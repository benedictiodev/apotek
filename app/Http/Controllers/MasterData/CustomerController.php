<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request) {
        $data = MasterCustomer::query()
            ->where("name", "like", "%$request->search%")
            ->paginate(10);
        return view('dashboard.master-data.customer.index', [
            'data' => $data
        ]);
    }

    public function create() {
        return view('dashboard.master-data.customer.create');
    }

    public function store(Request $request) {
        $validate = $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $store = MasterCustomer::create([
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
            return redirect()->route('dashboard.master-data.customer')->with('success', "Berhasil menambahkan data pelanggan");
        } else {
            return redirect()->route('dashboard.master-data.customer')->with('failed', "Gagal menambahkan data pelanggan");
        }
    }

    public function edit($id) {
        $data = MasterCustomer::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();

        if ($data && $data->company_id == Auth::user()->company_id) {
            return view('dashboard.master-data.customer.edit', ["data" => $data]);
        } else {
            return redirect()->route('dashboard.master-data.customer')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function update(Request $request, $id) {
        $validate = $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $store = MasterCustomer::where('id', $id)
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
            return redirect()->route('dashboard.master-data.customer')->with('success', "Berhasil merubah data pelanggan");
        } else {
            return redirect()->route('dashboard.master-data.customer')->with('failed', "Gagal merubah data pelanggan");
        }
    }

    public function destroy($id)
    {
        // $product = Product::find($id);
        $delete = MasterCustomer::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->delete();

        if ($delete) {
            return redirect()->route('dashboard.master-data.customer')->with('success', "Berhasil menghapus data pelanggan");
        } else {
            return redirect()->route('dashboard.master-data.customer')->with('failed', "Gagal menghapus data pelanggan");
        }
    }
}
