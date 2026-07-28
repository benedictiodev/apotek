<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request) {
        $data = Order::query()
            ->where('company_id', Auth::user()->company_id)
            ->paginate(10);
        return view('dashboard.order.index', [
            'data' => $data
        ]);
    }

    public function create() {
        
        // $category = MasterProductCategory::query()
        //     ->where('company_id', Auth::user()->company_id)
        //     ->get();

        // $uom = MasterUom::query()
        //     ->where('company_id', Auth::user()->company_id)
        //     ->get();

        // $supplier = MasterSupplier::query()
        //     ->where('company_id', Auth::user()->company_id)
        //     ->get();

        return view('dashboard.order.create', [
            // 'category' => $category,
            // 'uom' => $uom,
            // 'supplier' => $supplier
        ]);
    }
}
