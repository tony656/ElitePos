<?php

namespace App\Http\Controllers;

use App\Models\recevingModel;
use Illuminate\Http\Request;
use App\Models\ordersModel;
use App\Models\productsModel;
use App\Models\salsModel;
use App\Models\couponModel;
use App\Models\expensesModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlyReportExport;
use illuminate\Support\Facades\Auth;
use App\Models\cashSubmitModel;

class salsController extends Controller
{
    public function index(Request $req) {
        $user = Auth::user();


        if(!empty($req->input('selectedDate'))) {


            $thedate = $req->input('selectedDate');
            $start_date = $thedate . ' 00:00:00';
            $end_date = $thedate . ' 23:59:59';
        
     $sales = DB::table('sales')->where('account', session('account'))
    ->select(
        'sales_id',
        DB::raw('MAX(salesName) as salesName'),
        DB::raw('MAX(cName) as cName'),
        DB::raw('MAX(cPhone) as cPhone'),
        DB::raw('MAX(status) as status'),
        DB::raw('MAX(served_by) as served_by'),
        DB::raw('MAX(created_at) as created_at'),
        DB::raw('SUM(totalPrice) as totalPrice')
    )
    ->whereBetween('created_at', [$start_date, $end_date])
    ->where('salesName', '!=', '')
    ->groupBy('sales_id')
    ->orderByDesc(DB::raw('MAX(id)')) // optional: sort by latest sale
    ->get();

        
            $Tdiscount = salsModel::where('account', session('account'))->whereBetween('created_at', [$start_date, $end_date])
            ->sum('discount');

             $Tdebt = salsModel::where('account', session('account'))
                            ->where('status', 'Debt')
                            ->whereBetween('created_at', [$start_date, $end_date])
                            ->sum('totalPrice');
             

            $Tsale = salsModel::where('account', session('account'))->whereBetween('created_at', [$start_date, $end_date])
            ->sum('totalPrice');
    
            $Tproduct = salsModel::where('account', session('account'))->whereBetween('created_at', [$start_date, $end_date])
            ->sum('pQuantity');

            $additionalExpenses = expensesModel::whereBetween('created_at', [$start_date, $end_date])->sum('amount');

            $sumBuyingPrice = salsModel::join('products', 'sales.productId', '=', 'products.product_id')
            ->where('sales.account', session('account'))
            ->whereBetween('sales.created_at', [$start_date, $end_date])
            ->sum('products.bPrice');
    
            $TNetProfit = $Tsale - $sumBuyingPrice - $additionalExpenses;

        } else {
            
           $sales = salsModel::selectRaw('
        sales_id,
        MAX(salesName) as salesName,
        MAX(cName) as cName,
        MAX(status) as status,
        MAX(served_by) as served_by,
        MAX(created_at) as created_at,
        SUM(totalPrice) as totalAmount
    ')
    ->where('account', session('account'))
    ->where('salesName', '!=', '')
    ->groupBy('sales_id')
    ->orderByRaw('MAX(id) DESC')
    ->take(20)
    ->get();


                            $start_date = date("Y-m-01") . ' 00:00:00';
                            $end_date = date("Y-m-31") . ' 23:59:59';

                            $Tdiscount = salsModel::where('account', session('account'))->whereBetween('created_at', [$start_date, $end_date])
                            ->sum('discount');
                          
                            $Tdebt = salsModel::where('account', session('account'))
                            ->where('status', 'Debt')
                            ->whereBetween('created_at', [$start_date, $end_date])
                            ->sum('totalPrice');

                            $Tsale = salsModel::where('account', session('account'))->whereBetween('created_at', [$start_date, $end_date])
                            ->sum('totalPrice');
                    
                            $Tproduct = salsModel::where('account', session('account'))->whereBetween('created_at', [$start_date, $end_date])
                            ->sum('pQuantity');

                            $additionalExpenses = expensesModel::where('account', session('account'))->whereBetween('created_at', [$start_date, $end_date])->sum('amount');

                           $sumBuyingPrice = salsModel::join('products', 'sales.productId', '=', 'products.product_id')
    ->whereBetween('sales.created_at', [$start_date, $end_date])
    ->where('sales.account', session('account')) // ✅ specify the table
    ->sum('products.bPrice');

                    
                            $TNetProfit = $Tsale - $sumBuyingPrice - $additionalExpenses;
        }

   

        $Mstart_date = date("Y-m-01") . ' 00:00:00';
        $Mend_date = date("Y-m-31") . ' 23:59:59';

        
        $Mosales = salsModel::where('account', session('account'))->where('productId', '!=', '')
                            ->get();

        $Mdebt = salsModel::where('account', session('account'))
                            ->where('status', 'Debt')
                            ->whereBetween('created_at', [$start_date, $end_date])
                            ->sum('totalPrice');
        $Mdiscount = salsModel::where('account', session('account'))->whereBetween('created_at', [$Mstart_date, $Mend_date])
        ->sum('discount');

        $Msale = salsModel::where('account', session('account'))->whereBetween('created_at', [$Mstart_date, $Mend_date])
        ->sum('totalPrice');

        $Mproduct = salsModel::where('account', session('account'))->whereBetween('created_at', [$Mstart_date, $Mend_date])
        ->sum('pQuantity');


        $Mosales = salsModel::where('account', session('account'))->where('productId', '!=', '')
        ->whereBetween('created_at', [$Mstart_date, $Mend_date])
        ->get();

        $additionalExpenses = expensesModel::where('account', session('account'))->whereBetween('created_at', [$Mstart_date, $Mend_date])->sum('amount');

        $sumBuyingPrice = salsModel::join('products', 'sales.productId', '=', 'products.product_id')
        ->whereBetween('sales.created_at', [$Mstart_date, $Mend_date])
        ->where('sales.account', session('account'))
        ->sum('products.bPrice');

        $MoNetProfit = $Msale - $sumBuyingPrice - $additionalExpenses;

        $monthlySaleDates = DB::table('sales')
        ->where('account', session('account'))
    ->selectRaw('DATE(created_at) as sale_date')
    ->whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->where('salesName', '!=', '')
    ->groupBy('sale_date')
    ->pluck('sale_date')
    ->toArray();

    $data = compact(
        'sales', 'Tsale', 'Tproduct','Tdebt', 'Tdiscount', 'Mdiscount', 'Msale', 'Mproduct', 'MoNetProfit', 'TNetProfit','monthlySaleDates','Mdebt'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.sales', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.sales', $data);
    }
    }

    public function fullReport(Request $req) {

    $Mstart_date = date('Y-m-01 00:00:00');
$Mend_date   = date('Y-m-t 23:59:59');

;
$dates = \DB::table(\DB::raw("
    (
        SELECT DATE('$Mstart_date' + INTERVAL seq DAY) as report_date
        FROM (
            SELECT @row := @row + 1 AS seq
            FROM (
                SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3
                UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
                UNION ALL SELECT 8 UNION ALL SELECT 9
            ) t1,
            (
                SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3
                UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
                UNION ALL SELECT 8 UNION ALL SELECT 9
            ) t2,
            (SELECT @row := -1) t0
        ) seqs
        WHERE DATE('$Mstart_date' + INTERVAL seq DAY) <= DATE('$Mend_date')
    ) dates
"));

$salesByDate = salsModel::where('account', session('account'))
    ->whereBetween('created_at', [$Mstart_date, $Mend_date])
    ->selectRaw('DATE(created_at) as sale_date')
    ->selectRaw('SUM(totalPrice) as Msales')
    ->selectRaw('MAX(paid) as Mpaid')
    ->selectRaw('SUM(credit) as Mcredit')
    ->selectRaw('SUM(discount) as Mdisc')
    ->selectRaw('SUM(CASE WHEN status = "Debt" THEN totalPrice ELSE 0 END) as MDebt')
    ->groupByRaw('DATE(created_at)');

$expensesByDate = expensesModel::where('account', session('account'))
    ->whereBetween('created_at', [$Mstart_date, $Mend_date])
    ->selectRaw('DATE(created_at) as exp_date')
    ->selectRaw('SUM(amount) as Mexpenses')
    ->groupByRaw('DATE(created_at)');

$receivingCreditByDate = recevingModel::where('account', session('account'))
    ->where('isDebt', 1)
    ->whereBetween('created_at', [$Mstart_date, $Mend_date])
     ->selectRaw('
        DATE(created_at) as rec_date,
        SUM(price * COALESCE(quantity, 1)) as receivings_credit
    ')
    ->groupByRaw('DATE(created_at)');

$receivingByDate = recevingModel::where('account', session('account'))
    ->where('isPaid', 1)
    ->whereBetween('created_at', [$Mstart_date, $Mend_date])
    ->selectRaw('
        DATE(created_at) as rec_date,
        SUM(price * COALESCE(quantity, 1)) as receivings_paid
    ')
    ->groupByRaw('DATE(created_at)');

$cashSubmngByDate = cashSubmitModel::where('account', session('account'))
    ->whereBetween('submitted_at', [$Mstart_date, $Mend_date])
    ->selectRaw('DATE(submitted_at) as cash_date')
    ->selectRaw('SUM(submitted_cash) as cash')
    ->groupByRaw('DATE(submitted_at)');


$report = $dates
    ->leftJoinSub($salesByDate, 'sales', function ($join) {
        $join->on('dates.report_date', '=', 'sales.sale_date');
    })
    ->leftJoinSub($expensesByDate, 'expenses', function ($join) {
        $join->on('dates.report_date', '=', 'expenses.exp_date');
    })
    ->leftJoinSub($receivingCreditByDate, 'receivings_credit', function ($join) {
        $join->on('dates.report_date', '=', 'receivings_credit.rec_date');
    })
    ->leftJoinSub($receivingByDate, 'receivings_paid', function ($join) {
        $join->on('dates.report_date', '=', 'receivings_paid.rec_date');
    })
    ->leftJoinSub($cashSubmngByDate, 'cashsubmit', function ($join) {
        $join->on('dates.report_date', '=', 'cashsubmit.cash_date');
    })
    ->selectRaw('dates.report_date as date')
    ->selectRaw('COALESCE(sales.Msales, 0) as Msales')
    ->selectRaw('COALESCE(sales.Mcredit, 0) as Mcredit')
    ->selectRaw('COALESCE(sales.Mpaid, 0) as Mpaid')
    ->selectRaw('COALESCE(sales.Mdisc, 0) as Mdisc')
    ->selectRaw('COALESCE(sales.MDebt, 0) as MDebt')
    ->selectRaw('COALESCE(expenses.Mexpenses, 0) as Mexpenses')
    ->selectRaw('COALESCE(receivings_credit.receivings_credit, 0) as receivings_credit')
    ->selectRaw('COALESCE(receivings_paid.receivings_paid, 0) as receivings_paid')
    ->selectRaw('COALESCE(cashsubmit.cash, 0) as submitted_cash')
    ->orderBy('dates.report_date')
    ->get();


    $data = compact(
        'report'
        );


    return view('admin.dailyReport', $data);

    }

    public function cashSubmit(Request $req)
{
    // 1. Validate input
    $data = $req->validate([
        'cash_amount'     => 'required|numeric|min:0',
        'submission_date' => 'required|date',
        'date'            => 'required|date', // modalDate (report date)
    ]);

    // 2. Normalize values
    $cashAmount = $data['cash_amount'];
    $reportDate = $data['date'];
    $submitDate = $data['submission_date'];
    $account    = session('account');

    // 3. Prevent duplicate submission for same date
    $exists = cashSubmitModel::where('account', $account)
        ->whereDate('report_date', $reportDate)
        ->get();

    if ($exists->count() > 0) {
        return back()->with([
            'error' => 'Cash already submitted for this date.'
        ]);
    }

    // 4. Save
        $exists = new cashSubmitModel();
        $exists->submitted_cash = $cashAmount;
        $exists->report_date = $reportDate;
        $exists->submitted_at = $submitDate;
        $exists->account = $account;
        $exists->save();

    return redirect()->back()->with('success', 'Cash submitted successfully.');
}

    public function viewSales(Request $req) {

        $user = Auth::user();

        
        if(!empty($req->input('salesName'))) {

            $salesName = $req->input(key: 'salesName');

            session(['salesNameId' => $salesName]);
        }

        $salesName = session('salesNameId');

        $sales = salsModel::where('account', session('account'))->where('sales_id', '=', $salesName)->first();
        
        $salez = DB::table('sales')
        ->where('account', session('account'))
->where('salesName', '=', $sales->salesName)              
->sum('totalPrice');
              $couponCode = $sales->coupons;

              $getAmount = DB::table('coupons')->where('account', session('account'))->where('couponCode', '=', $couponCode)->first();

                $salex = DB::table('sales')
                                  ->where('account', session('account'))->
                                  where('sales_id', '=', $sales->sales_id)                      
                                  ->get();
                                  foreach($salex as $index => $sale) {
                                     $pNames = DB::table('products')
                                  ->where('account', session('account'))->
                                  where('product_id', '=', $sale->productId)                      
                                  ->first();
                                  }
          $getName = DB::table('system')->where('account', session('account'))->first();
                 $coupon = DB::table('coupons')->where('account', session('account'))->where('couponCode', '=', $sales->coupons)->first();

                 $data = compact(
        'sales','salez','salex','getAmount','pNames','getName','coupon'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.viewSales', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.viewSales', $data);
    }

    }

   public function export(Request $req)
{
    // Use same date filter logic as index()
    $thedate = $req->input('selectedDate');
    $start_date = $thedate ? $thedate . ' 00:00:00' : date("Y-m-01") . ' 00:00:00';
    $end_date   = $thedate ? $thedate . ' 23:59:59' : date("Y-m-t") . ' 23:59:59';

    // Fetch same data as index() but include 'account'
    $sales = DB::table('sales')
        ->select(
            'account',
            'sales_id',
            DB::raw('MAX(salesName) as salesName'),
            DB::raw('MAX(cName) as cName'),
            DB::raw('MAX(served_by) as served_by'),
            DB::raw('MAX(created_at) as created_at'),
            DB::raw('SUM(totalPrice) as totalPrice')
        )
        ->whereBetween('created_at', [$start_date, $end_date])
        ->where('salesName', '!=', '')
        ->groupBy('account', 'sales_id')
        ->orderBy('account')
        ->orderByDesc(DB::raw('MAX(id)'))
        ->get();

    // Totals
    $Tdiscount = salsModel::whereBetween('created_at', [$start_date, $end_date])->sum('discount');
    $Tsale = salsModel::whereBetween('created_at', [$start_date, $end_date])->sum('totalPrice');
    $Tproduct = salsModel::whereBetween('created_at', [$start_date, $end_date])->sum('pQuantity');

    $additionalExpenses = expensesModel::whereBetween('created_at', [$start_date, $end_date])->sum('amount');
    $sumBuyingPrice = salsModel::join('products', 'sales.productId', '=', 'products.product_id')
        ->whereBetween('sales.created_at', [$start_date, $end_date])
        ->where('sales.account', session('account'))
        ->sum('products.bPrice');

    $TNetProfit = $Tsale - $sumBuyingPrice - $additionalExpenses;

    return Excel::download(
        new MonthlyReportExport($sales, $start_date, $end_date, $Tdiscount, $Tsale, $Tproduct, $TNetProfit),
        'sales-report-' . now()->format('Y-m-d') . '.xlsx'
    );
}


}