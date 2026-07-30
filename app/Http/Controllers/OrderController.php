<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use App\Models\CashMonthly;
use App\Models\MasterCustomer;
use App\Models\MasterPaymentMethod;
use App\Models\Order;
use App\Models\OrderDetail;
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
            $store = Order::create([
                'id_order' => $id_order,
                'sequence' => $next_sequence,
                'customer_id' => $request->customer_id ?? null,
                'cashier_id' => Auth::user()->id,
                'date_time' => now(),
                'total_price_item' => (int) str_replace('.', '', $validate['total_price_item']),
                'discount' => $validate['discounts'] ?? 0,
                'total_discount' => (int) str_replace('.', '', $request->total_discounts ?? 0),
                'total_payment' => (int) str_replace('.', '', $validate['total_payment']),
                'payment' => (int) str_replace('.', '', $validate['payment']),
                'change' => (int) str_replace('.', '', $validate['change']),
                'payment_method' => $validate['payment_method'],
                'status' => 'done',
                'remarks' => '',
                'company_id' => Auth::user()->company_id,
            ]);

            foreach ($request->product_id as $key => $item) {
                $totalPrice = ((int) str_replace('.', '', $request->price[$key])) * $request->quantity[$key];
                $storeDetail = OrderDetail::create([
                    'product_id' => $item,
                    'uom_id' => $request->uom_id[$key],
                    'price' => (int) str_replace('.', '', $request->price[$key]),
                    'quantity' => $request->quantity[$key],
                    'discount' => $request->discount[$key] ?? 0,
                    'total_price' => $totalPrice,
                    'total_discount' => $totalPrice * ($request->discount[$key] ?? 0) / 100,
                    'amount' => (int) str_replace('.', '', $request->total_price[$key]),
                    'order_id' => $store->id,
                ]);
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
