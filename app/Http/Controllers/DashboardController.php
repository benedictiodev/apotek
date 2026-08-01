<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\ClosingCycle;
use App\Models\Fund;
use App\Models\MasterPaymentMethod;
use App\Models\MasterProductCategory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItems;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        $month = Carbon::now()->format('Y-m');

        $order_date_now = Order::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(total_payment) as total_payment"))
            ->where('status', 'done')
            ->where('date_time', 'like', Carbon::now()->toDateString() . '%')
            ->first();

        $order_month_now = Order::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(total_payment) as total_payment"))
            ->where('status', 'done')
            ->where('date_time', 'like', $month . '%')
            ->first();

        $order_item = OrderDetail::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(quantity) as quantity"))
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('status', 'done')
            ->where('date_time', 'like', $month . '%')
            ->first();

        $order_month_now_chart = Order::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(total_payment) as total_payment"), DB::raw('Date(date_time) AS date') )
            ->where('status', 'done')
            ->where('date_time', 'like', $month . '%')
            ->groupBy(DB::raw('DATE(date_time)'))
            ->orderBy(DB::raw('DATE(date_time)'), 'ASC')
            ->get();

        $result_chart_order_label = array();
        $result_chart_order_value = array();
        $index_data_chart = 0;
        for ($i = 1; $i <= (int) Carbon::now()->endOfMonth()->format('d'); $i++) {
            $days = $month . '-' . ($i < 10 ? '0' . $i : $i);
            $fund = 0;
            if ($index_data_chart < count($order_month_now_chart) && $order_month_now_chart[$index_data_chart]->date == $days) {
                $fund = $order_month_now_chart[$index_data_chart]->total_payment;
                $index_data_chart += 1;
            }
            array_push($result_chart_order_label, $i < 10 ? '0' . $i : $i);
            array_push($result_chart_order_value, $fund);
        }

        $category = MasterProductCategory::where('company_id', Auth::user()->company_id)->get();
        $product = Product::where('company_id', Auth::user()->company_id)->get();
        $fund_master = MasterPaymentMethod::where('company_id', Auth::user()->company_id)->get();

        return view('dashboard.dashboard.index', [
            'category' => count($category),
            'product' => count($product),
            'fund_master' => count($fund_master),
            'order_date_now' => $order_date_now->total_payment,
            'order_month_now' => $order_month_now->total_payment,
            'order_item' => $order_item->quantity,
            'result_chart_order_label' => $result_chart_order_label,
            'result_chart_order_value' => $result_chart_order_value,
        ]);
    }
}
