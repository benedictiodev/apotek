<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use App\Models\CashMonthly;
use App\Models\MasterPaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashFlowController extends Controller
{
    public function list_daily(Request $request) {
        $company_id = Auth::user()->company_id;
        $periode = Carbon::now()->format('Y-m-d');
        if ($request->periode) {
            $periode = $request->periode;
        }

        $fund = MasterPaymentMethod::where('company_id', Auth::user()->company_id)->get();
        $result_fund = array();
        foreach($fund AS $item) {
            array_push($result_fund, (object) array(
                'name' => $item->name,
                'cash_in' => 0,
                'cash_out' => 0
            ));
        }

        $cash_in = CashIn::where('date_time', 'like', $periode . '%')
            ->select('*', 'type AS type_fund', DB::raw('"cash-in" AS type'))
            ->where('company_id', '=', $company_id)->orderBy('date_time')->get();
        // $cash_out = CashOut::where('datetime', 'like', $periode . '%')
        //     ->select('*', 'type AS type_fund', DB::raw('"cash-out" AS type'))
        //     ->where('company_id', '=', $company_id)->orderBy('datetime')->get();

        $total_cash_in = 0;
        foreach($cash_in AS $item) {
            $total_cash_in += (int)$item->fund;

            foreach($result_fund as $key => $value) {
                if ($value->name == $item->type_fund) {
                    $result_fund[$key]->cash_in += (int)$item->fund;
                    break;
                }
            }
        }
        $total_cash_out = 0;
        foreach([] AS $item) {
            $total_cash_out += (int)$item->fund;

            // foreach($result_fund as $key => $value) {
            //     if ($value->name == $item->type_fund) {
            //         $result_fund[$key]->cash_out += (int)$item->fund;
            //         break;
            //     }
            // }
        }

        $result = $cash_in;
        // $result = $cash_in->push(...$cash_out);

        $sortedResult = $result->sortBy(['datetime']);
        $processedData = collect($sortedResult);

        $perPage = 5; // Replace 15 with the desired number of items per page
        $page = request()->get('page', 1); // Get the current page number from the request, default to 1
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $processedData->slice(($page - 1) * $perPage, $perPage),
            $processedData->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('dashboard.finance.cash-flow.daily', [
            'data' => $paginatedData, 
            'total_cash_in' => $total_cash_in, 
            'total_cash_out' => $total_cash_out,
            'result_fund' => $result_fund,
        ]);
    }

    public function list_monthly (Request $request) {
        $periode = Carbon::now()->format('Y-m');
        if ($request->periode) {
            $periode = $request->periode;
        }
        $data = CashMonthly::where('company_id', Auth::user()->company_id)
            ->where('date', 'like', $periode . '%')->orderBy('date')->get();
        $fund = MasterPaymentMethod::where('company_id', Auth::user()->company_id)->get();

        $total_cash_in = 0;
        $total_cash_out = 0;
        $total_amount = 0;

        foreach($data AS $item) {
            $item->total_amount = $total_amount + $item->amount;
            $total_cash_in += $item->kredit;
            $total_cash_out += $item->debit;
            $total_amount += $item->amount;
        } 

        return view('dashboard.finance.cash-flow.monthly', [
            'data' => $data, 
            'total_cash_in' => $total_cash_in, 
            'total_cash_out' => $total_cash_out,
            'total_amount' => $total_amount,
            'fund' => $fund,
        ]);
    }
}
