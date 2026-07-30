<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller
{
    public function index(Request $request) {
        $data = MasterPaymentMethod::query()
            ->where('company_id', Auth::user()->company_id)
            ->where("name", "like", "%$request->search%")
            ->paginate(10);
        return view('dashboard.master-data.payment-method.index', [
            'data' => $data
        ]);
    }

    public function create() {
        return view('dashboard.master-data.payment-method.create');
    }

    public function store(Request $request) {
        $validate = $request->validate([
            'name' => 'required',
        ]);

        $store = MasterPaymentMethod::create([
            'name' => $validate['name'],
            'company_id' => Auth::user()->company_id,
        ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.payment-method')->with('success', "Berhasil menambahkan data metode pembayaran");
        } else {
            return redirect()->route('dashboard.master-data.payment-method')->with('failed', "Gagal menambahkan data metode pembayaran");
        }
    }

    public function edit($id) {
        $data = MasterPaymentMethod::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();

        if ($data && $data->company_id == Auth::user()->company_id) {
            return view('dashboard.master-data.payment-method.edit', ["data" => $data]);
        } else {
            return redirect()->route('dashboard.master-data.payment-method')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function update(Request $request, $id) {
        $validate = $request->validate([
            'name' => 'required',
        ]);

        $store = MasterPaymentMethod::where('id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->update([
                'name' => $validate['name'],
            ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.payment-method')->with('success', "Berhasil merubah data metode pembayaran");
        } else {
            return redirect()->route('dashboard.master-data.payment-method')->with('failed', "Gagal merubah data metode pembayaran");
        }
    }

    public function destroy($id)
    {
        // $product = Product::find($id);
        $delete = MasterPaymentMethod::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->delete();

        if ($delete) {
            return redirect()->route('dashboard.master-data.payment-method')->with('success', "Berhasil menghapus data metode pembayaran");
        } else {
            return redirect()->route('dashboard.master-data.payment-method')->with('failed', "Gagal menghapus data metode pembayaran");
        }
    }
}
