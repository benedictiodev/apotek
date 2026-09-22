<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockOpname;
use App\Models\Product;
use App\Services\StockOpname\StockOpnameService;
use Exception;

class StockOpnameController extends Controller
{
    // 1. Halaman Index (List Opname)
    public function index()
    {
        $opnames = StockOpname::with('user')->orderBy('created_at', 'desc')->get();
        return view('dashboard.stock-opname.index', compact('opnames'));
    }

    // 2. Simpan Sesi Opname Baru (Dari Modal Index)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        $opname = StockOpname::create([
            'user_id' => auth("web")->id(),
            'title' => $request->title,
            'note' => $request->note,
            'status' => 'NEW',
            'started_at' => now(),
        ]);

        return redirect()->route('dashboard.stock-opname.show', $opname->id)->with('success', 'Sesi Opname berhasil dibuat.');
    }

    // 3. Halaman Detail / Progress (Dinamic Read-Only or Edit)
    public function show($id)
    {
        $opname = StockOpname::with('details.product', 'details.productStock', 'user')->findOrFail($id);

        // Tentukan apakah form boleh diedit
        $isEditable = in_array($opname->status, ['NEW', 'DRAFT']);

        return view('dashboard.stock-opname.detail', compact('opname', 'isEditable'));
    }

    // 4. API Pencarian Barcode (Dipanggil AJAX saat scan)
    public function scanBarcode(Request $request)
    {
        $search = $request->search;

        $product = Product::with(['Stock' => function ($query) {
            $query->where('expired_date', '>', now())->orderBy('expired_date', 'asc');
        }])
            ->where('code', $search)
            ->orWhere('name', 'like', "%{$search}%")
            ->first();

        if (!$product) return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan'], 404);
        if ($product->stock->isEmpty()) return response()->json(['status' => 'error', 'message' => 'Tidak ada batch aktif'], 404);

        return response()->json([
            'status' => 'success',
            'product' => $product,
            'batches' => $product->stock
        ]);
    }

    // 5. Submit Perubahan atau Finalisasi Opname
    public function submitWorkspace(Request $request, $id, StockOpnameService $service)
    {
        $request->validate([
            'action' => 'required|in:DRAFT,COMPLETE',
            'items' => 'array'
        ]);

        try {
            $opname = StockOpname::findOrFail($id);
            if (!in_array($opname->status, ['NEW', 'DRAFT'])) {
                return response()->json(['status' => 'error', 'message' => 'Opname sudah terkunci.'], 403);
            }

            $service->processOpname($opname, $request->input('items', []), $request->input('action'));

            if ($request->input('action') === 'COMPLETE') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Opname diselesaikan.',
                    'redirect' => route('dashboard.stock-opname.index')
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Draft tersimpan.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
