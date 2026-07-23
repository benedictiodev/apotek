<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\ClosingCycle;
use App\Models\Fund;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        // $month = Carbon::now()->format('Y-m');

        // $order_date_now = Order::where('company_id', Auth::user()->company_id)
        //     ->select(DB::raw("SUM(total_payment) as total_payment"))
        //     ->where('status', 'done')
        //     ->where('datetime', 'like', Carbon::now()->toDateString() . '%')
        //     ->first();

        // $order_month_now = Order::where('company_id', Auth::user()->company_id)
        //     ->select(DB::raw("SUM(total_payment) as total_payment"))
        //     ->where('status', 'done')
        //     ->where('datetime', 'like', $month . '%')
        //     ->first();

        // $order_item = OrderItems::where('company_id', Auth::user()->company_id)
        //     ->select(DB::raw("SUM(quantity) as quantity"))
        //     ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
        //     ->where('status', 'done')
        //     ->where('datetime', 'like', $month . '%')
        //     ->first();

        // $order_month_now_chart = Order::where('company_id', Auth::user()->company_id)
        //     ->select(DB::raw("SUM(total_payment) as total_payment"), DB::raw('Date(datetime) AS date') )
        //     ->where('status', 'done')
        //     ->where('datetime', 'like', $month . '%')
        //     ->groupBy(DB::raw('DATE(datetime)'))
        //     ->orderBy(DB::raw('DATE(datetime)'), 'ASC')
        //     ->get();

        // $result_chart_order_label = array();
        // $result_chart_order_value = array();
        // $index_data_chart = 0;
        // for ($i = 1; $i <= (int) Carbon::now()->endOfMonth()->format('d'); $i++) {
        //     $days = $month . '-' . ($i < 10 ? '0' . $i : $i);
        //     $fund = 0;
        //     if ($index_data_chart < count($order_month_now_chart) && $order_month_now_chart[$index_data_chart]->date == $days) {
        //         $fund = $order_month_now_chart[$index_data_chart]->total_payment;
        //         $index_data_chart += 1;
        //     }
        //     array_push($result_chart_order_label, $i < 10 ? '0' . $i : $i);
        //     array_push($result_chart_order_value, $fund);
        // }

        // $result_closing_cycle_label = array();
        // $result_closing_cycle_value_equite = array();
        // $result_closing_cycle_value_profit = array();
        // $index_data_chart = 0;
        // $closing_cycle = ClosingCycle::where('company_id', Auth::user()->company_id)
        //     ->where('periode', 'like', Carbon::now()->format('Y') . '%')
        //     ->get();
        // for ($i = 1; $i <= 12; $i++) {
        //     $periode = Carbon::now()->format('Y') . '-' . ($i < 10 ? '0' . $i : $i);
        //     $equite = 0;
        //     $profit = 0;
        //     if ($index_data_chart < count($closing_cycle) && $closing_cycle[$index_data_chart]->periode == $periode) {
        //         $equite = $closing_cycle[$index_data_chart]->equity;
        //         $profit = $closing_cycle[$index_data_chart]->profit;
        //         $index_data_chart += 1;
        //     }
        //     array_push($result_closing_cycle_label, $i < 10 ? '0' . $i : $i);
        //     array_push($result_closing_cycle_value_equite, $equite);
        //     array_push($result_closing_cycle_value_profit, $profit);
        // }

        // $category = CategoryProduct::where('company_id', Auth::user()->company_id)->get();
        // $product = Product::where('company_id', Auth::user()->company_id)
        //     ->leftJoin('category_products', 'category_products.id', '=', 'products.category_id')
        //     ->get();
        // $fund_master = Fund::where('company_id', Auth::user()->company_id)->get();

        // return view('dashboard.dashboard.index', [
        //     'category' => count($category),
        //     'product' => count($product),
        //     'fund_master' => count($fund_master),
        //     'order_date_now' => $order_date_now->total_payment,
        //     'order_month_now' => $order_month_now->total_payment,
        //     'order_item' => $order_item->quantity,
        //     'result_chart_order_label' => $result_chart_order_label,
        //     'result_chart_order_value' => $result_chart_order_value,
        //     'result_closing_cycle_label' => $result_closing_cycle_label,
        //     'result_closing_cycle_value_equite' => $result_closing_cycle_value_equite,
        //     'result_closing_cycle_value_profit' => $result_closing_cycle_value_profit,
        // ]);

        return view('dashboard.dashboard.index', [
            'category' => 0,
            'product' => 0,
            'fund_master' => 0,
            'order_date_now' => 0,
            'order_month_now' => 0,
            'order_item' => 0,
            'result_chart_order_label' => [],
            'result_chart_order_value' => [],
            'result_closing_cycle_label' => [],
            'result_closing_cycle_value_equite' => 0,
            'result_closing_cycle_value_profit' => 0,
        ]);
    }
}
