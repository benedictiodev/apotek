<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use App\Models\CashMonthly;
use App\Models\MasterCustomer;
use App\Models\MasterPaymentMethod;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request) {
        // $data = Order::query()
        //     ->with(['User'])
        //     ->where('company_id', Auth::user()->company_id);

        $periode = Carbon::now()->format('Y-m-d');

        if ($request->periode) {
            $periode = $request->periode;
        }

        $data = Order::where('date_time', 'like', $periode . '%')
            ->where("company_id", Auth::user()->company_id)
            ->where("status", "done")
            ->orderBy('date_time');

        $total = 0;
        foreach ($data->get() as $item) {
            $total += (int)$item->total_payment;
        }

        return view('dashboard.order.index', [
            'data' => $data->paginate(10),
            'total' => $total,
        ]);
    }

    public function create() {
        $customers = MasterCustomer::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        $paymentMethod = MasterPaymentMethod::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        return view('dashboard.order.create', [
            'customers' => $customers,
            'paymentMethod' => $paymentMethod,
        ]);
    }

    public function store(Request $request) {
        try {
            $validate = $request->validate([
                'total_price_item' => 'required',
                'discounts' => 'required',
                'tax' => 'required',
                'total_payment' => 'required',
                'payment' => 'required',
                'change' => 'required',
                'payment_method' => 'required',
            ]);

            $data_order = Order::where('company_id', Auth::user()->company_id)
                ->where('date_time', 'like', Carbon::now()->toDateString() . '%')
                ->orderBy('sequence', 'DESC')
                ->first();
            $next_sequence = $data_order ? $data_order->sequence + 1 : 1;
            $id_order = Carbon::now()->format('Ymd') . str_pad($next_sequence, 4, '0', STR_PAD_LEFT);;

            DB::beginTransaction();

            $totalProfit = 0;
            $InsertToOrderDetail = [];
            foreach ($request->product_id as $key => $item) {
                $dataProductDetail = ProductDetail::with(['Uom'])
                    ->where('id', $request->product_detail_id[$key])
                    ->first();

                $totalPrice = ((int) str_replace('.', '', $request->price[$key])) * $request->quantity[$key];
                $quantityOnBaseUom = $request->quantity[$key] * $dataProductDetail->contains;
                $amount = (int) str_replace('.', '', $request->total_price[$key]);
                $totalDiscountOrder = $amount * ($validate['discounts'] ?? 0) / 100;
                $fixAmount = $amount - $totalDiscountOrder;
                $basePrice = $dataProductDetail->price & $request->quantity[$key];

                $InsertToOrderDetail[] = [
                    'product_id' => $item,
                    'product_detail_id' => $request->product_detail_id[$key],
                    'uom' => $dataProductDetail->Uom->name,
                    'quantity_on_base_uom' => $quantityOnBaseUom,
                    'price' => (int) str_replace('.', '', $request->price[$key]),
                    'quantity' => $request->quantity[$key],
                    'total_price' => $totalPrice,
                    'discount' => $request->discount[$key] ?? 0,
                    'total_discount' => $totalPrice * ($request->discount[$key] ?? 0) / 100,
                    'amount' => $amount,
                    'discount_order' => $validate['discounts'] ?? 0,
                    'total_discount_order' => $totalDiscountOrder,
                    'fix_amount' => $fixAmount,
                    'base_price' => $basePrice,
                    'profit' => $fixAmount - $basePrice,
                ];

                $totalProfit += $fixAmount - $basePrice;

                $dataProduct = Product::where('id', $item)->first();
                Product::where('id', $item)
                    ->update([
                        'stock' => $dataProduct->stock - $quantityOnBaseUom,
                    ]);
            }

            $store = Order::create([
                'id_order' => $id_order,
                'sequence' => $next_sequence,
                'customer_id' => $request->customer_id ?? null,
                'cashier_id' => Auth::user()->id,
                'date_time' => now(),
                'total_price_item' => (int) str_replace('.', '', $validate['total_price_item']),
                'discount' => $validate['discounts'] ?? 0,
                'total_discount' => (int) str_replace('.', '', $request->total_discounts ?? 0),
                'tax' => $validate['tax'] ?? 0,
                'total_tax' => (int) str_replace('.', '', $request->total_tax ?? 0),
                'total_payment' => (int) str_replace('.', '', $validate['total_payment']),
                'payment' => (int) str_replace('.', '', $validate['payment']),
                'change' => (int) str_replace('.', '', $validate['change']),
                'profit' => $totalProfit,
                'payment_method' => $validate['payment_method'],
                'status' => 'done',
                'remarks' => '',
                'company_id' => Auth::user()->company_id,
            ]);

            foreach ($InsertToOrderDetail as $dataOrderDetail) {
                $dataOrderDetail['order_id'] = $store->id;
                $storeDetail = OrderDetail::create($dataOrderDetail);
            }

            CashIn::create([
                'company_id' => Auth::user()->company_id,
                'fund' => (int) str_replace('.', '', $validate['total_payment']),
                'remark' => null,
                'date_time' => Carbon::now()->toDateTimeString(),
                'type' => '',
                'order_id' => $store->id,
                'remarks_from_master' => null,
            ]);

            $cash_monthly = CashMonthly::where("company_id", Auth::user()->company_id)
                ->where("date", Carbon::now()->toDateString())->first();

            if ($cash_monthly) {
                CashMonthly::where("id", $cash_monthly->id)->update([
                    "kredit" => (int) $cash_monthly->kredit + ((int) str_replace('.', '', $validate['total_payment'])),
                    "amount" => (int) $cash_monthly->amount + ((int) str_replace('.', '', $validate['total_payment'])),
                    "total_amount" => (int) $cash_monthly->total_amount + ((int) str_replace('.', '', $validate['total_payment'])),
                ]);
            } else {
                CashMonthly::create([
                    "company_id" => Auth::user()->company_id,
                    "debit" => 0,
                    "kredit" => ((int) str_replace('.', '', $validate['total_payment'])),
                    "amount" => ((int) str_replace('.', '', $validate['total_payment'])),
                    "total_amount" => ((int) str_replace('.', '', $validate['total_payment'])),
                    "date" => Carbon::now()->toDateString()
                ]);
            }

            if ($store) {
                DB::commit();
                return redirect()->route('dashboard.order')->with('success', "Berhasil menambahkan data order");
            } else {
                DB::rollBack();
                return redirect()->route('dashboard.order')->with('failed', "Gagal menambahkan data order");
            }
        } catch (Exception $error) {
            DB::rollBack();
            throw $error;
            return redirect()->route('dashboard.order')->with('failed', "Gagal menambahkan data order");
        }
    }
}
