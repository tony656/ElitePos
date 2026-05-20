<?php

namespace App\Http\Controllers;

use App\Models\debtsModel;
use App\Models\recevingModel;
use App\Models\accountModel;
use App\Models\stock;
use Illuminate\Http\Request;
use App\Models\ordersModel;
use App\Models\productsModel;
use Carbon\Carbon;
use App\Models\salsModel;
use App\Models\couponModel;
use App\Models\expensesModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlyReportExport;
use Illuminate\Support\Facades\Auth;
use App\Models\cashSubmitModel;
use App\Models\logModal;
use App\Models\BankingTransfer;
use App\Models\madeni;
use App\Models\UserAccount;
use App\Models\BankingChip;
use App\Models\Offer;

use function getSessionAccountName;

class salsController extends Controller
{
    public function index(Request $req) {
        $user = Auth::user();
        
        // Handle shop selection
        if ($req->has('shop_id')) {
            $shopId = $req->input('shop_id');
            // Verify user has access to this shop
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                session(['selected_shop_id' => $shopId]);
            } else {
                $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
                if (in_array($shopId, $assignedAccountIds)) {
                    session(['selected_shop_id' => $shopId]);
                }
            }
        }
        
        // Determine which shop to use
        $selectedShopId = session('selected_shop_id');
        if (!$selectedShopId) {
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                $selectedShopId = accountModel::select('id')->orderBy('created_at', 'desc')->first()?->id;
            } else {
                $primaryAccount = UserAccount::where('user_id', $user->id)->where('is_primary', true)->first();
                if ($primaryAccount) {
                    $selectedShopId = $primaryAccount->account;
                } else {
                    $firstAccount = UserAccount::where('user_id', $user->id)->first();
                    $selectedShopId = $firstAccount ? $firstAccount->account : null;
                }
            }
            if ($selectedShopId) {
                session(['selected_shop_id' => $selectedShopId]);
            }
        }
        
        // For non-admin users, ensure the selected shop is one they have access to
        if (strtolower(trim($user->levelStatus)) !== 'admin' && $selectedShopId) {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (!in_array($selectedShopId, $assignedAccountIds)) {
                // If selected shop is not assigned, fall back to primary or first assigned account
                $primaryAccount = UserAccount::where('user_id', $user->id)->where('is_primary', true)->first();
                if ($primaryAccount) {
                    $selectedShopId = $primaryAccount->account;
                } else {
                    $firstAccount = UserAccount::where('user_id', $user->id)->first();
                    $selectedShopId = $firstAccount ? $firstAccount->account : null;
                }
                session(['selected_shop_id' => $selectedShopId]);
            }
        }
        
        $accountId = $selectedShopId;
        
        // Get all accessible shops for the user
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $allShops = accountModel::select('id', 'name', 'location')->orderBy('created_at', 'desc')->get();
        } else {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($assignedAccountIds)) {
                $allShops = collect();
            } else {
                $allShops = accountModel::whereIn('id', $assignedAccountIds)->select('id', 'name', 'location')->get();
            }
        }

        if(!empty($req->input('selectedDate'))) {
            $thedate = $req->input('selectedDate');
            $start_date = $thedate . ' 00:00:00';
            $end_date = $thedate . ' 23:59:59';
        
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                $sales = DB::table('sales')->where('account', $accountId)
                    ->select(
                        'sales_id',
                        'account',
                        DB::raw('MAX(salesName) as salesName'),
                        DB::raw('MAX(cName) as cName'),
                        DB::raw('MAX(cPhone) as cPhone'),
                        DB::raw('MAX(status) as status'),
                        DB::raw('MAX(served_by) as served_by'),
                        DB::raw('MAX(created_at) as created_at'),
                        DB::raw('MAX(totalPrice) as totalPrice'),
                        DB::raw('SUM(pQuantity) as totalQuantity'),
                        DB::raw('SUM(paid) as totalPaid'),
                        DB::raw('SUM(credit) as totalCredit')
                    )
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->where(function($query) {
                        $query->where('salesName', '!=', '')->orWhereNull('salesName');
                    })
                    ->groupBy('sales_id', 'account')
                    ->orderByDesc(DB::raw('MAX(id)'))
                    ->get();
            } else {
                $sales = DB::table('sales')->where('account', $accountId)
                    ->select(
                        'sales_id',
                        'account',
                        DB::raw('MAX(salesName) as salesName'),
                        DB::raw('MAX(cName) as cName'),
                        DB::raw('MAX(cPhone) as cPhone'),
                        DB::raw('MAX(status) as status'),
                        DB::raw('MAX(served_by) as served_by'),
                        DB::raw('MAX(created_at) as created_at'),
                        DB::raw('MAX(totalPrice) as totalPrice'),
                        DB::raw('SUM(pQuantity) as totalQuantity'),
                        DB::raw('SUM(paid) as totalPaid'),
                        DB::raw('SUM(credit) as totalCredit')
                    )
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->where(function($query) {
                        $query->where('salesName', '!=', '')->orWhereNull('salesName');
                    })
                    ->groupBy('sales_id', 'account')
                    ->orderByDesc(DB::raw('MAX(id)'))
                    ->get();
            }

            $Tdiscount = salsModel::where('account', $accountId)->whereBetween('created_at', [$start_date, $end_date])->sum('discount');
            $TdiscountIncrease = salsModel::where('account', $accountId)->whereBetween('created_at', [$start_date, $end_date])->sum('discount_increase');
            
            $salesAgg = salsModel::where('account', $accountId)
                ->where('offered_items', '!=', 1)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->selectRaw('sales_id, MAX(totalPrice) as totalPrice, MAX(credit) as credit')
                ->groupBy('sales_id');

            $Tdebt = \DB::query()->fromSub($salesAgg, 's')->sum('credit');
            $Tsale = \DB::query()->fromSub($salesAgg, 's')->sum('totalPrice');
            $Tproduct = salsModel::where('account', $accountId)->whereBetween('created_at', [$start_date, $end_date])->sum('pQuantity');
            $additionalExpenses = expensesModel::whereBetween('created_at', [$start_date, $end_date])->sum('amount');

            // Optimized: Use separate query with proper indexes
            $sumBuyingPrice = salsModel::where('sales.account', $accountId)
                ->whereBetween('sales.created_at', [$start_date, $end_date])
                ->join('products', 'sales.productId', '=', 'products.product_id')
                ->selectRaw('SUM(products.bPrice * sales.pQuantity) as total_cost')
                ->first();
            $sumBuyingPrice = $sumBuyingPrice->total_cost ?? 0;
            $TNetProfit = $Tsale - $sumBuyingPrice - $additionalExpenses;

        } else {
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                $sales = salsModel::selectRaw('
                    sales_id,
                    MAX(account) as account,
                    MAX(salesName) as salesName,
                    MAX(cName) as cName,
                    MAX(status) as status,
                    MAX(served_by) as served_by,
                    MAX(created_at) as created_at,
                    MAX(totalPrice) as totalAmount,
                    SUM(pQuantity) as totalQuantity,
                    SUM(paid) as totalPaid,
                    SUM(credit) as totalCredit
                ')
                ->where('account', $accountId)
                ->where('offered_items', '!=', 1)
                ->where(function($query) {
                    $query->where('salesName', '!=', '')->orWhereNull('salesName');
                })
                ->groupBy('sales_id', 'account')
                ->orderByRaw('MAX(id) DESC')
                ->take(20)
                ->get();
            } else {
                $sales = salsModel::selectRaw('
                    sales_id,
                    MAX(account) as account,
                    MAX(salesName) as salesName,
                    MAX(cName) as cName,
                    MAX(status) as status,
                    MAX(served_by) as served_by,
                    MAX(created_at) as created_at,
                    MAX(totalPrice) as totalAmount,
                    SUM(pQuantity) as totalQuantity,
                    SUM(paid) as totalPaid,
                    SUM(credit) as totalCredit
                ')
                ->where('account', $accountId)
                ->where('offered_items', '!=', 1)
                ->where(function($query) {
                    $query->where('salesName', '!=', '')->orWhereNull('salesName');
                })
                ->groupBy('sales_id', 'account')
                ->orderByRaw('MAX(id) DESC')
                ->take(20)
                ->get();
            }

            $start_date = date("Y-m-01") . ' 00:00:00';
            $end_date = date("Y-m-31") . ' 23:59:59';

            $Tdiscount = salsModel::where('account', $accountId)->whereBetween('created_at', [$start_date, $end_date])->sum('discount');
            $TdiscountIncrease = salsModel::where('account', $accountId)->whereBetween('created_at', [$start_date, $end_date])->sum('discount_increase');
            
            $monthlySalesAgg = salsModel::where('account', $accountId)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->selectRaw('sales_id, MAX(totalPrice) as totalPrice, MAX(credit) as credit')
                ->groupBy('sales_id');

            $Tdebt = \DB::query()->fromSub($monthlySalesAgg, 's')->where('credit', '>', 0)->sum('totalPrice');
            $Tsale = \DB::query()->fromSub($monthlySalesAgg, 's')->sum('totalPrice');
            $Tproduct = salsModel::where('account', $accountId)->whereBetween('created_at', [$start_date, $end_date])->sum('pQuantity');
            $additionalExpenses = expensesModel::where('account', $accountId)->whereBetween('created_at', [$start_date, $end_date])->sum('amount');

            // Optimized: Use separate query with proper indexes
            $sumBuyingPrice = salsModel::where('sales.account', $accountId)
                ->whereBetween('sales.created_at', [$start_date, $end_date])
                ->join('products', 'sales.productId', '=', 'products.product_id')
                ->selectRaw('SUM(products.bPrice * sales.pQuantity) as total_cost')
                ->first();
            $sumBuyingPrice = $sumBuyingPrice->total_cost ?? 0;
            $TNetProfit = $Tsale - $sumBuyingPrice - $additionalExpenses;
        }

        // Monthly calculations (for current month)
        $Mstart_date = date("Y-m-01") . ' 00:00:00';
        $Mend_date = date("Y-m-31") . ' 23:59:59';

        $monthlySalesAgg = salsModel::where('account', $accountId)
            ->whereBetween('created_at', [$Mstart_date, $Mend_date])
            ->selectRaw('sales_id, MAX(totalPrice) as totalPrice, MAX(credit) as credit')
            ->groupBy('sales_id');

        $Mdebt = \DB::query()->fromSub($monthlySalesAgg, 's')->where('credit', '>', 0)->sum('totalPrice');
        $Mdiscount = salsModel::where('account', $accountId)->whereBetween('created_at', [$Mstart_date, $Mend_date])->sum('discount');
        $MdiscountIncrease = salsModel::where('account', $accountId)->whereBetween('created_at', [$Mstart_date, $Mend_date])->sum('discount_increase');
        $Msale = \DB::query()->fromSub($monthlySalesAgg, 's')->sum('totalPrice');
        $Mproduct = salsModel::where('account', $accountId)->whereBetween('created_at', [$Mstart_date, $Mend_date])->sum('pQuantity');

        $additionalExpensesMonthly = expensesModel::where('account', $accountId)->whereBetween('created_at', [$Mstart_date, $Mend_date])->sum('amount');

        // Optimized: Use separate query with proper indexes
        $sumBuyingPriceMonthly = salsModel::where('sales.account', $accountId)
            ->whereBetween('sales.created_at', [$Mstart_date, $Mend_date])
            ->join('products', 'sales.productId', '=', 'products.product_id')
            ->selectRaw('SUM(products.bPrice * sales.pQuantity) as total_cost')
            ->first();
        $sumBuyingPriceMonthly = $sumBuyingPriceMonthly->total_cost ?? 0;

        $MoNetProfit = $Msale - $sumBuyingPriceMonthly - $additionalExpensesMonthly;

        $monthlySaleDates = DB::table('sales')
            ->where('account', $accountId)
            ->selectRaw('DATE(created_at) as sale_date')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where(function($query) {
                $query->where('salesName', '!=', '')->orWhereNull('salesName');
            })
            ->groupBy('sale_date')
            ->pluck('sale_date')
            ->toArray();

        // Pass allShops to views
        $data = compact(
            'sales', 'Tsale', 'Tproduct', 'Tdebt', 'Tdiscount', 'TdiscountIncrease',
            'Mdiscount', 'MdiscountIncrease', 'Msale', 'Mproduct', 'MoNetProfit',
            'TNetProfit', 'monthlySaleDates', 'Mdebt', 'allShops'
        );

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.sales', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.sales', $data);
        }
    }

    public function fullReport(Request $req) {
        $monthParam = $req->input('month', date('Y-m'));

        try {
            $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $monthParam);
        } catch (\Exception $e) {
            $monthDate = \Carbon\Carbon::now();
            $monthParam = $monthDate->format('Y-m');
        }

        $Mstart_date = $monthDate->copy()->startOfMonth()->format('Y-m-d H:i:s');
        $Mend_date   = $monthDate->copy()->endOfMonth()->format('Y-m-d H:i:s');
        
        $user = Auth::user();
        
        // Handle shop selection from request (for both admin and regular users)
        if ($req->has('shop_id')) {
            $shopId = $req->input('shop_id');
            // Verify user has access to this shop
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                session(['selected_shop_id' => $shopId]);
            } else {
                $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
                if (in_array($shopId, $assignedAccountIds)) {
                    session(['selected_shop_id' => $shopId]);
                }
            }
        }
        
        // Determine which account to use for the report (single account only)
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admins can select a shop via session, or use an account that has sales data
            $selectedShopId = session('selected_shop_id');
            if (!$selectedShopId) {
                // Find any account that has sales in the selected month
                $selectedShopId = salsModel::whereBetween('created_at', [$Mstart_date, $Mend_date])
                    ->where(function($q) { $q->where('salesName', '!=', '')->orWhereNull('salesName'); })
                    ->select('account')
                    ->distinct()
                    ->first()?->account;
                
                // If no account has sales this month, fall back to any account
                if (!$selectedShopId) {
                    $selectedShopId = accountModel::select('id')->first()?->id;
                }
            }
            $accountId = $selectedShopId;
            // For admin, we still need allShops for the shop selector UI
            $allShops = accountModel::select('id', 'name', 'location')
                        ->where('created_at', '<=', $Mend_date)
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            // Non-admins: use only their assigned account (primary or first)
            // This ensures they only see data from their own account
            $primaryAccount = UserAccount::where('user_id', $user->id)->where('is_primary', true)->first();
            if ($primaryAccount) {
                $accountId = $primaryAccount->account;
            } else {
                $firstAccount = UserAccount::where('user_id', $user->id)->first();
                $accountId = $firstAccount ? $firstAccount->account : null;
            }
            // For non-admins, allShops is just their single assigned account (for UI consistency)
            if ($accountId) {
                $allShops = accountModel::where('id', $accountId)->select('id', 'name', 'location')->get();
            } else {
                $allShops = collect();
            }
        }

        // Get the selected shop name for display
        $selectedShopName = null;
        if ($accountId) {
            $selectedShop = accountModel::find($accountId);
            $selectedShopName = $selectedShop ? $selectedShop->name : null;
        }

        // Build daily report for the single account only
        $report = collect();
        $currentDate = $monthDate->copy()->startOfMonth();
        $endDate = $monthDate->copy()->endOfMonth();
        
        while ($currentDate->lte($endDate)) {
            $dayStart = $currentDate->copy()->startOfDay()->format('Y-m-d H:i:s');
            $dayEnd = $currentDate->copy()->endOfDay()->format('Y-m-d H:i:s');
            
            // Initialize daily totals
            $dailyTotals = [
                'Mcash_sales' => 0,
                'Mcredit_sales' => 0,
                'Msales' => 0,
                'Mreturn' => 0,
                'cash_return' => 0,
                'credit_return' => 0,
                'Mdisc' => 0,
                'MdiscIncrease' => 0,
                'Mexpenses' => 0,
                'total_bank' => 0,
                'total_chip' => 0,
                'paidInvoices' => 0,
                'chipUsed' => 0,
                'receivingsPaid' => 0,
                'receivingsCredit' => 0,
                'paid_receivings' => 0,
                'submitted_cash' => 0,
                'Moffered' => 0,
                'total_returned' => 0,
            ];
            
            // Aggregate data from the single account for this day
            if ($accountId) {
                // Sales aggregates
                $salesAgg = salsModel::where('account', $accountId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->where(function($q) { $q->where('salesName', '!=', '')->orWhereNull('salesName'); })
                    ->selectRaw('
                        SUM(totalPrice) as total_sales,
                        SUM(COALESCE(return_amount, 0)) as total_return,
                        SUM(CASE WHEN status = "Paid" THEN paid WHEN status = "Partial" THEN paid ELSE 0 END) as cash_sales,
                        SUM(CASE WHEN status = "Debt" THEN credit WHEN status = "Partial" THEN credit ELSE 0 END) as credit_sales,
                        SUM(discount) as total_discount,
                        SUM(discount_increase) as total_discount_increase
                    ')
                    ->first();
                
                $dailyTotals['Msales'] += $salesAgg->total_sales ?? 0;
                $dailyTotals['Mreturn'] += $salesAgg->total_return ?? 0;
                $dailyTotals['Mcash_sales'] += $salesAgg->cash_sales ?? 0;
                $dailyTotals['Mcredit_sales'] += $salesAgg->credit_sales ?? 0;
                $dailyTotals['Mdisc'] += $salesAgg->total_discount ?? 0;
                $dailyTotals['MdiscIncrease'] += $salesAgg->total_discount_increase ?? 0;
                
                // Cash returns from sales returns
                $cashReturns = salsModel::where('account', $accountId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->where('status', 'Return')
                    ->where('transactionType', 'Cash')
                    ->sum('totalPrice');
                $dailyTotals['cash_return'] += $cashReturns;
                
                // Credit returns from sales returns
                $creditReturns = salsModel::where('account', $accountId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->where('status', 'Return')
                    ->where('transactionType', 'Credit')
                    ->sum('totalPrice');
                $dailyTotals['credit_return'] += $creditReturns;
                
                // Expenses
                $expenses = expensesModel::where('account', $accountId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->sum('amount');
                $dailyTotals['Mexpenses'] += $expenses;

                $banking = BankingTransfer::where('shop_id', $accountId)
                    ->whereBetween('transfer_date', [$dayStart, $dayEnd])
                    ->sum('amount');
                $dailyTotals['total_bank'] += $banking;

                $chip = BankingChip::where('shop_id', $accountId)
                    ->whereBetween('transfer_date', [$dayStart, $dayEnd])
                    ->sum('chip_amount');
                $dailyTotals['total_chip'] += $chip;
                
                // Paid invoices
                $paidInvoices = debtsModel::where('account', $accountId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->sum('amount');
                $dailyTotals['paidInvoices'] += $paidInvoices;
                
                $chipUsed = debtsModel::where('account', $accountId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->sum('chip_amount');
                $dailyTotals['chipUsed'] += $chipUsed;

                // Receivings (paid)
                $paidReceivings = recevingModel::where('account', $accountId)
                    ->where('isPaid', 1)
                    ->where(function($q) { $q->whereNull('status')->orWhere('status', '!=', 'Returned'); })
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->sum(DB::raw('price * COALESCE(quantity, 0)'));
                $dailyTotals['receivingsPaid'] += $paidReceivings;
                
                // Receivings (credit)
                $creditReceivings = recevingModel::where('account', $accountId)
                    ->where('isPaid', 0)
                    ->where(function($q) { $q->whereNull('status')->orWhere('status', '!=', 'Returned'); })
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->sum(DB::raw('price * COALESCE(quantity, 0)'));
                $dailyTotals['receivingsCredit'] += $creditReceivings;
                
                // Supplier payments (madeni)
                $supplierPayments = madeni::where('account', $accountId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->sum('amount');
                $dailyTotals['paid_receivings'] += $supplierPayments;
                
                // Cash submissions
                $cashSubmissions = cashSubmitModel::where('account', $accountId)
                    ->whereDate('report_date', $currentDate->format('Y-m-d'))
                    ->sum('submitted_cash');
                $dailyTotals['submitted_cash'] += $cashSubmissions;

                // Total returned from receivings (status = 'Returned' or is_return = 1)
                $totalReturned = recevingModel::where('account', $accountId)
                    ->where(function($q) {
                        $q->where('status', 'Returned')->orWhere('is_return', 1);
                    })
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->sum(DB::raw('price * COALESCE(quantity, 0)'));
                $dailyTotals['total_returned'] += $totalReturned;
            }
            
            // Calculate cash amount for the day
            $netCashSales = ($dailyTotals['Mcash_sales'] ?? 0) - ($dailyTotals['cash_return'] ?? 0);
            $cashAmount = $netCashSales
                        + ($dailyTotals['paidInvoices'] ?? 0)
                        + ($dailyTotals['MdiscIncrease'] ?? 0)
                        - ($dailyTotals['Mdisc'] ?? 0)
                        - ($dailyTotals['Mexpenses'] ?? 0)
                        - ($dailyTotals['receivingsPaid'] ?? 0);
            
            // Create daily report object
            $report->push((object)[
                'shop_id' => $accountId,
                'report_date' => $currentDate->format('Y-m-d'),
                'Mcash_sales' => $dailyTotals['Mcash_sales'],
                'Mcredit_sales' => $dailyTotals['Mcredit_sales'],
                'Msales' => $dailyTotals['Msales'],
                'Mreturn' => $dailyTotals['Mreturn'],
                'cash_return' => $dailyTotals['cash_return'],
                'credit_return' => $dailyTotals['credit_return'],
                'Mdisc' => $dailyTotals['Mdisc'],
                'MdiscIncrease' => $dailyTotals['MdiscIncrease'],
                'Mexpenses' => $dailyTotals['Mexpenses'],
                'total_bank' => $dailyTotals['total_bank'],
                'total_chip' => $dailyTotals['total_chip'],
                'paidInvoices' => $dailyTotals['paidInvoices'],
                'chip_used'     => $dailyTotals['chipUsed'],
                'receivingsPaid' => $dailyTotals['receivingsPaid'],
                'receivingsCredit' => $dailyTotals['receivingsCredit'],
                'paid_receivings' => $dailyTotals['paid_receivings'],
                'submitted_cash' => $dailyTotals['submitted_cash'],
                'cashAmount' => $cashAmount,
                'Moffered' => $dailyTotals['Moffered'],
                'total_returned' => $dailyTotals['total_returned'],
            ]);
            
            $currentDate->addDay();
        }

        // Totals
        $totals = (object)[
            'total_transactions' => 0,
            'cash_sales' => $report->sum('Mcash_sales') ?? 0,
            'credit_sales' => $report->sum('Mcredit_sales') ?? 0,
            'total_sales' => $report->sum('Msales') ?? 0,
            'total_return' => $report->sum('Mreturn') ?? 0,
            'cash_return' => $report->sum('cash_return') ?? 0,
            'credit_return' => $report->sum('credit_return') ?? 0,
            'discount' => $report->sum('Mdisc') ?? 0,
            'discount_increase' => $report->sum('MdiscIncrease') ?? 0,
            'expenses' => $report->sum('Mexpenses') ?? 0,
            'banking' => $report->sum('total_bank') ?? 0,
            'totalChip' => $report->sum('total_chip') ?? 0,
            'paid_invoices' => $report->sum('paidInvoices') ?? 0,
            'chip_used'     => $report->sum('chip_used') ?? 0,
            'profit' => ($report->sum('Mcash_sales') ?? 0) - ($report->sum('Mexpenses') ?? 0) - ($report->sum('receivingsPaid') ?? 0),
            'cash_receivings' => $report->sum('receivingsPaid') ?? 0,
            'credit_receivings' => $report->sum('receivingsCredit') ?? 0,
            'paid_receivings' => $report->sum('paid_receivings') ?? 0,
            'cash_amount' => $report->sum('cashAmount') ?? 0,
            'cash_submitted' => $report->sum('submitted_cash') ?? 0,
        ];

        // Calculate total distinct transactions for the single account
        if ($accountId) {
            $totals->total_transactions = salsModel::where('account', $accountId)
                ->whereBetween('created_at', [$Mstart_date, $Mend_date])
                ->where(function($q) { $q->where('salesName', '!=', '')->orWhereNull('salesName'); })
                ->distinct('sales_id')
                ->count('sales_id');
        }

        // Staff totals - for the single account only
        $allSalesForStaff = salsModel::where('account', $accountId)
            ->whereBetween('created_at', [$Mstart_date, $Mend_date])
            ->where(function($q) { $q->where('salesName', '!=', '')->orWhereNull('salesName'); })
            ->get();
        
        $monthlyByServed = $allSalesForStaff->groupBy('served_by')->map(function($sales, $served_by) {
            return (object)[
                'served_by' => $served_by,
                'total_sales' => $sales->sum('totalPrice'),
                'total_return' => $sales->where('status', 'Return')->sum('return_amount'),
                'total_cash' => $sales->where('status', 'Paid')->sum('paid'),
                'total_credit' => $sales->sum('credit'),
                'total_discount' => $sales->sum('discount'),
                'total_discount_increase' => $sales->sum('discount_increase'),
                'total_debt' => $sales->sum('credit'),
            ];
        })->values();
        
        $staffSalesByDate = $allSalesForStaff->groupBy(function($sale) {
            return date('Y-m-d', strtotime($sale->created_at));
        })->flatMap(function($daySales, $date) {
            return $daySales->groupBy('served_by')->map(function($sales, $served_by) use ($date) {
                return (object)[
                    'sale_date' => $date,
                    'staff_name' => $served_by,
                    'total_sales' => $sales->sum('totalPrice'),
                    'total_return' => $sales->where('status', 'Return')->sum('return_amount'),
                    'total_cash' => $sales->where('status', 'Paid')->sum('paid'),
                    'total_credit' => $sales->sum('credit'),
                    'total_discount' => $sales->sum('discount'),
                    'total_discount_increase' => $sales->sum('discount_increase'),
                    'total_debt' => $sales->sum('credit'),
                ];
            });
        })->values();

        $data = compact(
            'report', 'monthlyByServed', 'staffSalesByDate', 'monthParam', 'allShops', 'selectedShopName'
        );

         if (strtolower(trim($user->levelStatus)) === 'admin') {
             return view('admin.dailyReport', $data);
         }
         if(!empty($user->levelStatus)) {
             return view('user.dailyReport', $data);
         }
     }

    public function kpiDashboard(Request $req) {
        $monthParam = $req->input('month', date('Y-m'));
        $shopFilter = $req->input('shop_id'); // For users to filter by specific shop

        try {
            $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $monthParam);
        } catch (\Exception $e) {
            $monthDate = \Carbon\Carbon::now();
            $monthParam = $monthDate->format('Y-m');
        }

        $Mstart_date = $monthDate->copy()->startOfMonth()->format('Y-m-d H:i:s');
        $Mend_date   = $monthDate->copy()->endOfMonth()->format('Y-m-d H:i:s');
        
        $user = Auth::user();
        
        // Get accessible shops for the user
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admin: can see all shops
            $allShops = accountModel::select('id', 'name', 'location')->orderBy('name')->get();
            $accountIds = $allShops->pluck('id')->toArray();
        } else {
            // Non-admins: only their assigned accounts
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            $allShops = accountModel::whereIn('id', $accountIds)->select('id', 'name', 'location')->orderBy('name')->get();
        }

        // Apply shop filter if provided (for users)
        $filteredAccountIds = $accountIds;
        if ($shopFilter && in_array($shopFilter, $accountIds)) {
            $filteredAccountIds = [$shopFilter];
        }

        // Get all sales for the month for staff performance across selected shops
        $allSalesForStaff = collect();
        if (!empty($filteredAccountIds)) {
            $allSalesForStaff = salsModel::whereIn('account', $filteredAccountIds)
                ->whereBetween('created_at', [$Mstart_date, $Mend_date])
                ->where(function($q) { $q->where('salesName', '!=', '')->orWhereNull('salesName'); })
                ->get();
        }

        // Group by staff and calculate KPIs
        $staffKpis = $allSalesForStaff->groupBy('served_by')->map(function($sales, $served_by) {
            $totalSales = $sales->sum('totalPrice');
            $totalTransactions = $sales->count();
            $totalCash = $sales->where('status', 'Paid')->sum('paid');
            $totalCredit = $sales->sum('credit');
            $totalDiscount = $sales->sum('discount');
            $totalReturn = $sales->where('status', 'Return')->sum('return_amount');
            $totalDebt = $sales->sum('credit');
            
            // Calculate average transaction value
            $avgTransactionValue = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;
            
            // Calculate cash vs credit ratio
            $cashRatio = $totalSales > 0 ? ($totalCash / $totalSales) * 100 : 0;
            $creditRatio = $totalSales > 0 ? ($totalCredit / $totalSales) * 100 : 0;
            
            // Calculate discount rate
            $discountRate = $totalSales > 0 ? ($totalDiscount / $totalSales) * 100 : 0;
            
            // Calculate return rate
            $returnRate = $totalSales > 0 ? ($totalReturn / $totalSales) * 100 : 0;
            
            // Calculate debt ratio
            $debtRatio = $totalSales > 0 ? ($totalDebt / $totalSales) * 100 : 0;
            
            // Profit calculation (cash sales - expenses - returns)
            $profit = $totalCash - $totalReturn - $totalDiscount;
            $profitMargin = $totalSales > 0 ? ($profit / $totalSales) * 100 : 0;
            
            return (object)[
                'served_by' => $served_by,
                'total_sales' => $totalSales,
                'total_transactions' => $totalTransactions,
                'total_cash' => $totalCash,
                'total_credit' => $totalCredit,
                'total_discount' => $totalDiscount,
                'total_return' => $totalReturn,
                'total_debt' => $totalDebt,
                'avg_transaction_value' => $avgTransactionValue,
                'cash_ratio' => $cashRatio,
                'credit_ratio' => $creditRatio,
                'discount_rate' => $discountRate,
                'return_rate' => $returnRate,
                'debt_ratio' => $debtRatio,
                'profit' => $profit,
                'profit_margin' => $profitMargin,
            ];
        })->values();

        // Calculate overall totals for ranking
        $totalAllSales = $staffKpis->sum('total_sales');
        
        // Sort by total sales descending for ranking
        $staffKpis = $staffKpis->sortByDesc('total_sales')->values();

        // Add ranking
        $staffKpis = $staffKpis->map(function($kpi, $index) {
            $kpi->rank = $index + 1;
            return $kpi;
        });

        // For non-admin users, filter to show only their own KPI
        if (strtolower(trim($user->levelStatus)) !== 'admin') {
            $staffKpis = $staffKpis->filter(function($kpi) use ($user) {
                return $kpi->served_by === $user->name;
            })->values();
            // Recalculate total for single user
            $totalAllSales = $staffKpis->sum('total_sales');
        }

        $data = compact(
            'staffKpis', 'monthParam', 'totalAllSales', 'allShops'
        );

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.kpi', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.kpi', $data);
        }
    }

    public function customerKPI(Request $req)
    {
        $monthParam = $req->input('month', date('Y-m'));
        $shopFilter = $req->input('shop_id');

        try {
            $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $monthParam);
        } catch (\Exception $e) {
            $monthDate = \Carbon\Carbon::now();
            $monthParam = $monthDate->format('Y-m');
        }

        $Mstart_date = $monthDate->copy()->startOfMonth()->format('Y-m-d H:i:s');
        $Mend_date   = $monthDate->copy()->endOfMonth()->format('Y-m-d H:i:s');

        $user = Auth::user();

        // Customer KPI always shows data from ALL accounts/shops regardless of user role
        $allShops = accountModel::select('id', 'name', 'location')->orderBy('name')->get();
        $accountIds = $allShops->pluck('id')->toArray();

        // Apply shop filter if provided
        $filteredAccountIds = $accountIds;
        if ($shopFilter && in_array($shopFilter, $accountIds)) {
            $filteredAccountIds = [$shopFilter];
        }

        // Get all sales for the month grouped by customer
        $allSalesForCustomers = collect();
        if (!empty($filteredAccountIds)) {
            $allSalesForCustomers = salsModel::whereIn('account', $filteredAccountIds)
                ->whereBetween('created_at', [$Mstart_date, $Mend_date])
                ->where(function($q) { $q->where('salesName', '!=', '')->orWhereNull('salesName'); })
                ->get();
        }

        // Group by customer and calculate KPIs
        $customerKpis = $allSalesForCustomers->groupBy('cName')->map(function($sales, $customerName) use ($allSalesForCustomers) {
            $totalPurchases = $sales->count();
            $totalSpent = $sales->sum('totalPrice');
            $avgTransactionValue = $totalPurchases > 0 ? $totalSpent / $totalPurchases : 0;
            
            // Get unique dates for visit count
            $visitCount = $sales->groupBy(function($sale) {
                return \Carbon\Carbon::parse($sale->created_at)->format('Y-m-d');
            })->count();
            
            // Credit usage
            $totalCredit = $sales->sum('credit');
            $creditRatio = $totalSpent > 0 ? ($totalCredit / $totalSpent) * 100 : 0;
            
            // Payment status breakdown
            $paidAmount = $sales->where('status', 'Paid')->sum('paid');
            $partialAmount = $sales->where('status', 'Partial')->sum('paid');
            $debtAmount = $sales->where('status', 'Debt')->sum('credit');
            $partialDebt = $sales->where('status', 'Partial')->sum('credit');
            $totalPaid = $paidAmount + $partialAmount;
            $totalDebt = $debtAmount + $partialDebt;
            
            // Payment method breakdown
            $cashSales = $sales->where('transactionType', 'Cash')->sum('totalPrice');
            $creditSales = $sales->where('transactionType', 'Credit')->sum('totalPrice');
            $cashRatio = $totalSpent > 0 ? ($cashSales / $totalSpent) * 100 : 0;
            
            // Returns
            $totalReturns = $sales->where('status', 'Return')->sum('return_amount');
            $returnRate = $totalSpent > 0 ? ($totalReturns / $totalSpent) * 100 : 0;
            
            // Discounts
            $totalDiscount = $sales->sum('discount');
            $discountRate = $totalSpent > 0 ? ($totalDiscount / $totalSpent) * 100 : 0;
            
            // Get customer phone from first sale
            $customerPhone = $sales->first()->cPhone ?? 'N/A';
            
            // Get customer ID from customers table
            $customer = \App\Models\customerModel::where('name', $customerName)->first();
            $customerId = $customer ? $customer->id : null;

            return (object)[
                'customer_id' => $customerId,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'total_purchases' => $totalPurchases,
                'total_spent' => $totalSpent,
                'avg_transaction_value' => $avgTransactionValue,
                'visit_count' => $visitCount,
                'total_credit' => $totalCredit,
                'credit_ratio' => $creditRatio,
                'total_paid' => $totalPaid,
                'total_debt' => $totalDebt,
                'cash_sales' => $cashSales,
                'credit_sales' => $creditSales,
                'cash_ratio' => $cashRatio,
                'total_returns' => $totalReturns,
                'return_rate' => $returnRate,
                'total_discount' => $totalDiscount,
                'discount_rate' => $discountRate,
            ];
        })->values();

        // Sort by total spent descending
        $customerKpis = $customerKpis->sortByDesc('total_spent')->values();

        // Add ranking
        $customerKpis = $customerKpis->map(function($kpi, $index) {
            $kpi->rank = $index + 1;
            return $kpi;
        });

        $data = compact('customerKpis', 'monthParam', 'allShops');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.customerKPI', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.customerKPI', $data);
        }
    }

    public function cashSubmit(Request $req) {
        $user = Auth::user();
        
        if (!canUser('manage_shop_cash_submit') && !canUser('manage_full_report')) {
            return redirect()->back()->with('error', 'You do not have permission to submit cash.');
        }

        $shopId = $req->input('shop_id');
        $reportDate = $req->input('date');
        $submittedCash = $req->input('submitted_cash');

        // Verify user has access to this shop
        if ($user->levelStatus !== 'Admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (!in_array($shopId, $assignedAccountIds)) {
                return redirect()->back()->with('error', 'You do not have permission to submit cash for this shop.');
            }
        }

        $banking = BankingTransfer::where('shop_id', $shopId)
                ->where('transfer_date', $reportDate)
                ->get();
        $oldChipEntry = BankingChip::where('shop_id', $shopId)
                ->where('chip_amount', '>', 0)
                ->orderByDesc('id')
                ->first();
        
        if ($banking->isNotEmpty()) {
            $totalAmount = $banking->sum('amount');

            if ($totalAmount > $submittedCash) {
                $currentChip = $totalAmount - $submittedCash;
                $newChip = $currentChip + ($oldChipEntry->chip_amount ?? 0);

                // Update ALL banking chip records for this shop/date with the same chip values
                BankingChip::where('shop_id', $shopId)
                    ->where('transfer_date', $reportDate)
                    ->update([
                        'chip_amount' => $currentChip,
                        'available_chip' => $newChip
                    ]);
            }
        }
        if(!$banking) {
            return redirect()->back()->with('error', 'No banking transfer found for the specified shop and date. Please ensure a banking transfer is recorded before submitting cash.');
        }
        $existing = cashSubmitModel::where('account', $shopId)
            ->whereDate('report_date', $reportDate)
            ->first();

        if ($existing) {
            $existing->submitted_cash = $submittedCash;
            $existing->save();
            $message = 'Cash updated successfully.';
        } else {
            $create = new cashSubmitModel();
            $create->account = $shopId;
            $create->submitted_cash = $submittedCash;
            $create->report_date = $reportDate;
            $create->save();
            $message = 'Cash submitted successfully.';
        }

        $log = new logModal();
        $log->title = 'Cash Submission';
        $log->description = 'Cash of amount '.$req->input('submitted_cash').' '.($existing ? 'updated' : 'submitted').' by '.session('username');
        $log->save();

        return redirect()->back()->with('success', $message);
    }

    public function cashDelete(Request $req) {
        $user = Auth::user();
        
        if (!canUser('manage_shop_cash_submit') && !canUser('manage_full_report')) {
            return redirect()->back()->with('error', 'You do not have permission to delete cash submissions.');
        }

        $shopId = $req->input('shop_id');
        $date = $req->input('date');

        // Verify user has access to this shop
        if ($user->levelStatus !== 'Admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (!in_array($shopId, $assignedAccountIds)) {
                return redirect()->back()->with('error', 'You do not have permission to delete cash submissions for this shop.');
            }
        }
        
        $query = cashSubmitModel::where('account', $shopId)
            ->whereDate('report_date', $date);

        $submission = $query->first();

        if ($submission) {
            $submission->delete();
            // Reset chip to 0 for all banking chip records of that shop/date
            BankingChip::where('shop_id', $shopId)
                ->where('transfer_date', $date)
                ->update(['chip_amount' => 0, 'available_chip' => 0]);
            $log = new logModal();
            $log->title = 'Cash Submission Deleted';
            $log->description = 'Cash submission for shop ' . $shopId . ' on ' . $date . ' deleted by ' . session('username');
            $log->save();
            return redirect()->back()->with('success', 'Cash submission deleted successfully.');
        }

        return redirect()->back()->with('error', 'No cash submission found to delete.');
    }

    public function undoSales(Request $req) {
        $user = Auth::user();
        
        if (!canUser('manage_sales') && !canUser('manage_full_report')) {
            return redirect()->back()->with('error', 'You do not have permission to undo sales.');
        }

        $salesId = $req->input('salesName') ?? $req->input('sales_id');
        
        if (empty($salesId)) {
            return redirect()->back()->with('error', 'Invalid sales identifier.');
        }

        // Determine accessible accounts for the user based on role
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $accountIds = accountModel::pluck('id')->toArray();
        } else {
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
        }
        
        if (empty($accountIds)) {
            return redirect()->back()->with('error', 'No shops assigned to your account.');
        }

        DB::beginTransaction();
        
        try {
            $sales = salsModel::whereIn('account', $accountIds)
                ->where('salesName', $salesId)
                ->get();

            if ($sales->isEmpty()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No sales found to undo or you do not have permission.');
            }

            foreach ($sales as $sale) {
                $product = productsModel::whereIn('account', $accountIds)
                    ->where('product_id', $sale->productId)
                    ->first();
                
                if ($product) {
                    $product->quantity = $product->quantity + $sale->pQuantity;
                    $product->save();
                }

                $stockRecord = stock::where('productId', $sale->productId)
                    ->whereIn('account', $accountIds)
                    ->where('quantity', '>', 0)
                    ->orderBy('id', 'desc')
                    ->first();
                
                if ($stockRecord) {
                    $stockRecord->quantity = $stockRecord->quantity + $sale->pQuantity;
                    $stockRecord->save();
                } else {
                    $newStock = new stock();
                    $newStock->productId = $sale->productId;
                    $newStock->quantity = $sale->pQuantity;
                    $newStock->account = $sale->account;
                    $newStock->save();
                }

                $sale->delete();
            }
            
            DB::commit();
            
            $log = new logModal();
            $log->title = 'Sales Undone';
            $log->description = 'Sale ' . $salesId . ' undone by ' . session('username');
            $log->save();

            return redirect()->back()->with('success', 'Sale undone successfully. Stock quantities have been restored.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Undo Sales Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while undoing the sale. Please try again.');
        }
    }

    public function viewSales(Request $req) {
        $user = Auth::user();
        $salesId = $req->input(key: 'sales_id');
        
        if (empty($salesId)) {
            return redirect()->back()->with('error', 'Invalid sales identifier.');
        }
        
        // Determine which accounts to query
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $accountIds = accountModel::pluck('id')->toArray();
        } else {
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
        }
                
        $paid = salsModel::whereIn('account', $accountIds)
            ->where('sales_id', '=', $salesId)
            ->sum('paid');

        $allsales = salsModel::whereIn('account', $accountIds)
            ->where('sales_id', $salesId)
            ->get();
            
        $getName = DB::table('system')->whereIn('account', $accountIds)->first();

        $sales = salsModel::whereIn('account', $accountIds)->where('sales_id', $salesId)->first();

        $data = compact('sales', 'paid', 'getName', 'allsales');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.viewSales', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.viewSales', $data);
        }
    }

    public function export(Request $req) {
        $user = Auth::user();
        $thedate = $req->input('selectedDate');
        $start_date = $thedate ? $thedate . ' 00:00:00' : date("Y-m-01") . ' 00:00:00';
        $end_date   = $thedate ? $thedate . ' 23:59:59' : date("Y-m-t") . ' 23:59:59';

        // Determine which accounts to query
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $accountIds = accountModel::pluck('id')->toArray();
        } else {
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
        }

        $sales = DB::table('sales')
            ->select(
                'account',
                'sales_id',
                DB::raw('MAX(salesName) as salesName'),
                DB::raw('MAX(cName) as cName'),
                DB::raw('MAX(served_by) as served_by'),
                DB::raw('MAX(created_at) as created_at'),
                DB::raw('MAX(totalPrice) as totalPrice')
            )
            ->whereIn('account', $accountIds)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('salesName', '!=', '')
            ->groupBy('account', 'sales_id')
            ->orderBy('account')
            ->orderByDesc(DB::raw('MAX(id)'))
            ->get();

        $Tdiscount = salsModel::whereIn('account', $accountIds)->whereBetween('created_at', [$start_date, $end_date])->sum('discount');
        $TdiscountIncrease = salsModel::whereIn('account', $accountIds)->whereBetween('created_at', [$start_date, $end_date])->sum('discount_increase');
        
        $salesAgg = salsModel::whereIn('account', $accountIds)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->selectRaw('sales_id, MAX(totalPrice) as totalPrice')
            ->groupBy('sales_id');
        
        $Tsale = \DB::query()->fromSub($salesAgg, 's')->sum('totalPrice');
        $Tproduct = salsModel::whereIn('account', $accountIds)->whereBetween('created_at', [$start_date, $end_date])->sum('pQuantity');

        $additionalExpenses = expensesModel::whereIn('account', $accountIds)->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
        // Optimized: Use separate query with proper indexes
        $sumBuyingPrice = salsModel::whereIn('sales.account', $accountIds)
            ->whereBetween('sales.created_at', [$start_date, $end_date])
            ->join('products', 'sales.productId', '=', 'products.product_id')
            ->selectRaw('SUM(products.bPrice * sales.pQuantity) as total_cost')
            ->first();
        $sumBuyingPrice = $sumBuyingPrice->total_cost ?? 0;

        $TNetProfit = $Tsale - $sumBuyingPrice - $additionalExpenses;

        return Excel::download(
            new MonthlyReportExport($sales, $start_date, $end_date, $Tdiscount, $Tsale, $Tproduct, $TNetProfit),
            'sales-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportShopReport(Request $req) {
        // Support both single date and date range for export
        $dateFrom = $req->input('date_from');
        $dateTo = $req->input('date_to');
        $dateParam = $req->input('date', date('Y-m-d')); // Single date fallback
        $format = $req->input('format', 'excel');

        // Determine date range
        if ($dateFrom && $dateTo) {
            try {
                $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateFrom)->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateTo)->endOfDay();
                
                if ($endDate->lt($startDate)) {
                    $startDate = $endDate->copy()->startOfDay();
                    $endDate = $endDate->copy()->endOfDay();
                }
                
                $start_date = $startDate->format('Y-m-d H:i:s');
                $end_date = $endDate->format('Y-m-d H:i:s');
                $dateParam = $dateFrom . '_to_' . $dateTo;
            } catch (\Exception $e) {
                $reportDate = \Carbon\Carbon::now();
                $start_date = $reportDate->startOfDay()->format('Y-m-d H:i:s');
                $end_date = $reportDate->endOfDay()->format('Y-m-d H:i:s');
                $dateParam = $reportDate->format('Y-m-d');
            }
        } else {
            // Use single date (backward compatibility)
            try {
                $reportDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateParam);
            } catch (\Exception $e) {
                $reportDate = \Carbon\Carbon::now();
                $dateParam = $reportDate->format('Y-m-d');
            }
            $start_date = $reportDate->copy()->startOfDay()->format('Y-m-d H:i:s');
            $end_date = $reportDate->copy()->endOfDay()->format('Y-m-d H:i:s');
        }

        $user = Auth::user();
        
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $allShops = accountModel::select('id', 'name', 'location', 'phone', 'email')->get();
        } else {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            
            if (empty($assignedAccountIds)) {
                $allShops = collect();
            } else {
                $allShops = accountModel::whereIn('id', $assignedAccountIds)
                            ->select('id', 'name', 'location', 'phone', 'email')
                            ->get();
            }
        }

        $shopReports = collect();

        foreach ($allShops as $shop) {
            $salesAgg = salsModel::where('account', $shop->id)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->selectRaw('
                    sales_id,
                    MAX(totalPrice) as totalPrice,
                    MAX(paid) as paid,
                    MAX(credit) as credit,
                    MAX(status) as status,
                    SUM(return_amount) as return_amount,
                    SUM(discount) as discount,
                    SUM(discount_increase) as discount_increase,
                    SUM(CASE WHEN return_amount > 0 AND transactionType = "Cash" THEN return_amount ELSE 0 END) as cash_returns,
                    SUM(CASE WHEN return_amount > 0 AND transactionType = "Credit" THEN return_amount ELSE 0 END) as credit_returns
                ')
                ->groupBy('sales_id');

            $salesData = \DB::query()
                ->fromSub($salesAgg, 's')
                ->selectRaw('COUNT(*) as total_transactions')
                ->selectRaw('COALESCE(SUM(totalPrice), 0) as total_sales')
                ->selectRaw('COALESCE(SUM(return_amount), 0) as total_return')
                ->selectRaw('COALESCE(SUM(paid), 0) as total_cash')
                ->selectRaw('COALESCE(SUM(credit), 0) as total_credit')
                ->selectRaw('COALESCE(SUM(discount), 0) as total_discount')
                ->selectRaw('COALESCE(SUM(discount_increase), 0) as total_discount_increase')
                ->selectRaw('COALESCE(SUM(credit), 0) as total_debt')
                ->selectRaw('COALESCE(SUM(CASE WHEN status = "Paid" THEN paid ELSE 0 END), 0) as cash_sales')
                ->selectRaw('COALESCE(SUM(cash_returns), 0) as cash_returns')
                ->selectRaw('COALESCE(SUM(credit_returns), 0) as credit_returns')
                ->first();

            $expenses = expensesModel::where('account', $shop->id)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->selectRaw('COALESCE(SUM(amount), 0) as total_expenses')
                ->first();

            $paidInvoices = debtsModel::where('account', $shop->id)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->selectRaw('COALESCE(SUM(amount), 0) as total_paid_invoices')
                ->first();

            $creditReceivings = recevingModel::where('account', $shop->id)
                ->where('isPaid', 0)
                ->where(function($q) {
                    $q->whereNull('status')->orWhere('status', '!=', 'Returned');
                })
                ->whereBetween('created_at', [$start_date, $end_date])
                ->selectRaw('COALESCE(SUM(price * COALESCE(quantity, 0)), 0) as total_credit_receivings')
                ->selectRaw('COALESCE(SUM(COALESCE(quantity, 0)), 0) as credit_receiving_quantity')
                ->first();

            $paidReceivings = recevingModel::where('account', $shop->id)
                ->where('isPaid', 1)
                ->where(function($q) {
                    $q->whereNull('status')->orWhere('status', '!=', 'Returned');
                })
                ->whereBetween('created_at', [$start_date, $end_date])
                ->selectRaw('COALESCE(SUM(price * COALESCE(quantity, 0)), 0) as total_paid_receivings')
                ->selectRaw('COALESCE(SUM(COALESCE(quantity, 0)), 0) as paid_receivings_quantity')
                ->first();

            $returnedReceivings = recevingModel::where('account', $shop->id)
                ->where(function($q) {
                    $q->where('is_return', 1)->orWhere('status', 'Returned');
                })
                ->whereBetween('created_at', [$start_date, $end_date])
                ->selectRaw('COALESCE(SUM(price * COALESCE(quantity, 0)), 0) as total_returned_receivings')
                ->first();

            $supplierPayments = madeni::where('account', $shop->id)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->selectRaw('COALESCE(SUM(amount), 0) as total_supplier_payments')
                ->first();

            $cashSubmissions = cashSubmitModel::where('account', $shop->id)
                ->whereBetween(DB::raw('DATE(report_date)'), [date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])
                ->selectRaw('COALESCE(SUM(submitted_cash), 0) as total_cash_submitted')
                ->first();

            $profit = $salesData->total_sales - $salesData->total_debt - $expenses->total_expenses;

            $totalCashSales = $salesData->cash_sales ?? 0;
            $totalCreditSales = $salesData->total_credit ?? 0;
            $totalReturn = $salesData->total_return ?? 0;
            $cashReturns = $salesData->cash_returns ?? 0;
            $creditReturns = $salesData->credit_returns ?? 0;
            
            // Net cash sales after subtracting cash returns
            $netCashSales = $totalCashSales - $cashReturns;
            
            // Credit returns are already subtracted from total_credit in the aggregate
            $cash_return = $cashReturns;
            $credit_return = $creditReturns;

            $cashAmount = $netCashSales
                        + ($paidInvoices->total_paid_invoices ?? 0)
                        - ($expenses->total_expenses ?? 0)
                        - (($paidReceivings->total_paid_receivings ?? 0) + ($supplierPayments->total_supplier_payments ?? 0));

            $cashDifference = $cashAmount - $cashSubmissions->total_cash_submitted;

            $shopReports->push((object)[
                'shop_id' => $shop->id,
                'shop_name' => $shop->name ?? 'Unnamed Shop',
                'location' => $shop->location ?? 'N/A',
                'phone' => $shop->phone ?? 'N/A',
                'email' => $shop->email ?? 'N/A',
                'total_transactions' => $salesData->total_transactions,
                'cash_sales' => $salesData->total_cash,
                'credit_sales' => $salesData->total_credit,
                'total_sales' => $salesData->total_sales,
                'total_return'              => $salesData->total_return,
                'cash_return'               => $cash_return,
                'credit_return'             => -$credit_return,
                'returned_receivings'       => $returnedReceivings->total_returned_receivings ?? 0,
                'discount' => $salesData->total_discount,
                'expenses' => $expenses->total_expenses,
                'paid_invoices' => $paidInvoices->total_paid_invoices,
                'profit' => $profit,
                'cash_receivings' => $paidReceivings->total_paid_receivings,
                'credit_receivings' => $creditReceivings->total_credit_receivings,
                'credit_receiving_quantity' => $creditReceivings->credit_receiving_quantity,
                'paid_receivings' => $paidReceivings->total_paid_receivings,
                'paid_receivings_quantity' => $paidReceivings->paid_receivings_quantity,
                'cash_amount' => $cashAmount,
                'cash_submitted' => $cashSubmissions->total_cash_submitted,
                'cash_difference' => $cashDifference,
                'debt' => $salesData->total_debt,
                'has_sales' => $salesData->total_transactions > 0,
            ]);
        }

        $totals = (object)[
            'total_transactions' => $shopReports->sum('total_transactions'),
            'cash_sales' => $shopReports->sum('cash_sales'),
            'credit_sales' => $shopReports->sum('credit_sales'),
            'total_sales' => $shopReports->sum('total_sales'),
            'total_return' => $shopReports->sum('total_return'),
            'cash_return' => $shopReports->sum('cash_return'),
            'credit_return' => $shopReports->sum('credit_return'),
            'discount' => $shopReports->sum('discount'),
            'expenses' => $shopReports->sum('expenses'),
            'paid_invoices' => $shopReports->sum('paid_invoices'),
            'profit' => $shopReports->sum('profit'),
            'cash_receivings' => $shopReports->sum('cash_receivings'),
            'credit_receivings' => $shopReports->sum('credit_receivings'),
            'returned_receivings' => $shopReports->sum('returned_receivings'),
            'paid_receivings' => $shopReports->sum('paid_receivings'),
            'cash_amount' => $shopReports->sum('cash_amount'),
            'cash_submitted' => $shopReports->sum('cash_submitted'),
            'debt' => $shopReports->sum('debt'),
        ];

        if ($format === 'pdf') {
            return Excel::download(
                new ShopReportExport($shopReports, $totals, $dateParam),
                'shop-report-' . $dateParam . '.xlsx'
            );
        }

        return Excel::download(
            new ShopReportExport($shopReports, $totals, $dateParam),
            'shop-report-' . $dateParam . '.xlsx'
        );
    }

    public function searchSales(Request $req) {
        try {
            $user = Auth::user();
            if (!$user) {
                \Log::warning('SearchSales: User not authenticated');
                return response()->json(['sales' => [], 'success' => false, 'message' => 'Unauthorized'], 401);
            }
            
            \Log::info('SearchSales: User ' . $user->id . ' searching for: ' . $req->input('search', ''));
            
            $searchTerm = $req->input('search', '');
            
            // Determine which accounts to query
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                $accountIds = accountModel::pluck('id')->toArray();
            } else {
                $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            }

            \Log::info('SearchSales: Account IDs: ' . json_encode($accountIds));

            if (empty($accountIds)) {
                return response()->json(['sales' => [], 'success' => true, 'message' => 'No accounts found']);
            }

            $query = DB::table('sales')
                ->whereIn('account', $accountIds)
                ->select(
                    'sales_id',
                    DB::raw('MAX(salesName) as salesName'),
                    DB::raw('MAX(cName) as cName'),
                    DB::raw('MAX(cPhone) as cPhone'),
                    DB::raw('MAX(status) as status'),
                    DB::raw('MAX(served_by) as served_by'),
                    DB::raw('MAX(created_at) as created_at'),
                    DB::raw('MAX(totalPrice) as totalAmount'),
                    DB::raw('SUM(pQuantity) as totalQuantity'),
                    DB::raw('SUM(paid) as totalPaid'),
                    DB::raw('SUM(credit) as totalCredit')
                )
                ->where(function($query) {
                    $query->where('salesName', '!=', '')->orWhereNull('salesName');
                })
                ->groupBy('sales_id', 'account')
                ->orderByDesc(DB::raw('MAX(created_at)'));

            if (!empty($searchTerm)) {
                $query->where('cName', 'like', '%' . $searchTerm . '%');
            }

            $sales = $query->take(50)->get();
            
            \Log::info('SearchSales: Found ' . count($sales) . ' sales');

            return response()->json(['sales' => $sales, 'success' => true]);
            
        } catch (\Exception $e) {
            \Log::error('Search Sales Error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return response()->json([
                'sales' => [],
                'success' => false,
                'message' => 'An error occurred while searching sales. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function returnSaleToOrder(Request $req) {
        $user = Auth::user();
        $salesId = $req->input('sales_id');
        
        if (empty($salesId)) {
            return response()->json(['success' => false, 'message' => 'Sales ID is required']);
        }

        // Determine which accounts to query
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $accountIds = accountModel::pluck('id')->toArray();
        } else {
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
        }

        $salesItems = salsModel::whereIn('account', $accountIds)
            ->where('sales_id', $salesId)
            ->get();

        if ($salesItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No sale found']);
        }

        $firstItem = $salesItems->first();
        $orderId = 'ORD-' . time() . '-' . rand(1000, 9999);

        foreach ($salesItems as $sale) {
            $product = productsModel::whereIn('account', $accountIds)
                ->where('product_id', $sale->productId)
                ->first();
            if ($product) {
                $product->quantity = $product->quantity + $sale->pQuantity;
                $product->save();
            }
            
            $stock = stock::where('productId', $sale->productId)
                ->whereIn('account', $accountIds)
                ->where('quantity', '>', 0)
                ->orderBy('id', 'desc')
                ->first();
            if ($stock) {
                $stock->quantity = $stock->quantity + $sale->pQuantity;
                $stock->save();
            }

            $order = new ordersModel();
            $order->order_id = $orderId;
            $order->stockId = $sale->stockId;
            $order->orderName = $sale->salesName;
            $order->cName = $sale->cName;
            $order->cPhone = $sale->cPhone;
            $order->productId = $sale->productId;
            $order->pQuantity = $sale->pQuantity;
            $order->productPrice = $sale->productPrice;
            $order->totalPrice = $sale->totalPrice;
            $order->served_by = $sale->served_by;
            $order->status = 'Pending';
            $order->account = $sale->account;
            $order->created_at = $sale->created_at;
            $order->save();
        }

        salsModel::whereIn('account', $accountIds)
            ->where('sales_id', $salesId)
            ->delete();

        $log = new logModal();
        $log->title = 'Sales Returned to Order';
        $log->description = 'Sale ' . $salesId . ' returned to orders for editing by ' . session('username');
        $log->save();

        return response()->json([
            'success' => true,
            'message' => 'Sale returned to orders successfully',
            'order_id' => $orderId
        ]);
    }

    public function getSalesDates(Request $req) {
        $user = Auth::user();
        $year = $req->input('year', date('Y'));
        $month = $req->input('month', date('m'));
        $shopFilter = $req->input('shop_id'); // Optional shop filter
        
        $start_date = date("{$year}-{$month}-01") . ' 00:00:00';
        $end_date = date("{$year}-{$month}-t") . ' 23:59:59';
        
        // Determine which accounts to query
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            if ($shopFilter) {
                // Admin with specific shop filter
                $accountIds = [$shopFilter];
            } else {
                $accountIds = accountModel::pluck('id')->toArray();
            }
        } else {
            // Non-admins: only their assigned accounts
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
        }
        
        $dates = DB::table('sales')
            ->whereIn('account', $accountIds)
            ->selectRaw('DATE(created_at) as sale_date')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where(function($query) {
                $query->where('salesName', '!=', '')->orWhereNull('salesName');
            })
            ->groupBy('sale_date')
            ->pluck('sale_date')
            ->toArray();
        
        return response()->json(['dates' => $dates]);
    }

   public function AllShopReport(Request $req) {
     // Support both single date (for backward compatibility) and date range
     $dateFrom = $req->input('date_from');
     $dateTo = $req->input('date_to');
     $dateParam = $req->input('date', date('Y-m-d')); // Single date fallback
     $selectedShopId = $req->input('shop_id'); // Optional shop filter
     
     // Determine date range
     $usingDateRange = false;
     $startDate = null;
     $endDate = null;
     $reportDate = null;
     
     if ($dateFrom && $dateTo) {
         try {
             $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateFrom)->startOfDay();
             $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateTo)->endOfDay();
             
             // Ensure end date is not before start date
             if ($endDate->lt($startDate)) {
                 $startDate = $endDate->copy()->startOfDay();
                 $endDate = $endDate->copy()->endOfDay();
             }
             
             $start_date = $startDate->format('Y-m-d H:i:s');
             $end_date = $endDate->format('Y-m-d H:i:s');
             $dateParam = $dateFrom . ' to ' . $dateTo;
             $usingDateRange = true;
         } catch (\Exception $e) {
             // Fallback to single date
             $reportDate = \Carbon\Carbon::now();
             $start_date = $reportDate->startOfDay()->format('Y-m-d H:i:s');
             $end_date = $reportDate->endOfDay()->format('Y-m-d H:i:s');
             $dateParam = $reportDate->format('Y-m-d');
         }
     } else {
         // Use single date (backward compatibility)
         try {
             $reportDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateParam);
         } catch (\Exception $e) {
             $reportDate = \Carbon\Carbon::now();
             $dateParam = $reportDate->format('Y-m-d');
         }
         $start_date = $reportDate->copy()->startOfDay()->format('Y-m-d H:i:s');
         $end_date = $reportDate->copy()->endOfDay()->format('Y-m-d H:i:s');
     }
     
     $user = Auth::user();
     
     \Log::info('AllShopReport: Starting report generation', [
         'user_id' => $user->id,
         'user_level' => $user->levelStatus,
         'date_from' => $start_date,
         'date_to' => $end_date,
         'shop_filter' => $selectedShopId
     ]);
     
     // Get shops (same as before)
     if (strtolower(trim($user->levelStatus)) === 'admin') {
         $allShops = accountModel::select('id', 'name', 'location')
                     ->orderBy('created_at', 'desc')
                     ->get();
     } else {
         $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
         
         if (empty($assignedAccountIds)) {
             $allShops = collect();
         } else {
             $allShops = accountModel::whereIn('id', $assignedAccountIds)
                         ->select('id', 'name', 'location')
                         ->orderBy('created_at', 'desc')
                         ->get();
         }
     }
     
     \Log::info('AllShopReport: Total shops found: ' . $allShops->count());
     \Log::info('AllShopReport: Shop IDs: ' . implode(', ', $allShops->pluck('id')->toArray()));
     
     if ($allShops->isEmpty()) {
         \Log::warning('AllShopReport: No shops found for user');
         $data = compact('dateParam');
         return view('admin.shopReport', $data);
     }
     
     // Determine which shops to include in the report
     if ($selectedShopId && in_array($selectedShopId, $allShops->pluck('id')->toArray())) {
         $shopIds = [$selectedShopId];
     } else {
         $shopIds = $allShops->pluck('id')->toArray();
     }
    
    // Batch load all data with single queries
    \Log::info('AllShopReport: Starting batch queries for shop IDs: ' . implode(', ', $shopIds));
    \Log::info('AllShopReport: Date range: ' . $start_date . ' to ' . $end_date);
    
    // 1. Get all sales data aggregated by shop (OPTIMIZED)
    // Excludes returns - returns are tracked separately in $returnData
    // Uses composite index: (account, created_at, status) for fast filtering
    $salesData = salsModel::whereIn('account', $shopIds)
        ->whereBetween('created_at', [$start_date, $end_date])
        ->where('status', '!=', 'Return')
        ->where(function($q) { $q->where('salesName', '!=', '')->orWhereNull('salesName'); })
        ->selectRaw('account,
            COUNT(DISTINCT sales_id) as total_transactions,
            SUM(totalPrice) as total_sales,
            SUM(return_amount) as total_return,
            SUM(CASE WHEN status = "Debt" THEN credit ELSE 0 END) as total_credit,
            SUM(CASE WHEN status = "Partial" THEN credit ELSE 0 END) as partial_credit,
            SUM(discount) as total_discount,
            SUM(discount_increase) as total_discount_increase,
            SUM(CASE WHEN status = "Paid" THEN paid ELSE 0 END) as cash_sales_paid,
            SUM(CASE WHEN status = "Partial" THEN paid ELSE 0 END) as cash_sales_partial,
            SUM(credit) as total_debt')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Sales data count: ' . $salesData->count());
    \Log::info('AllShopReport: Sales data keys: ' . implode(', ', $salesData->keys()->toArray()));
     
    // 2. Get return transactions (status = 'Return') (OPTIMIZED)
    // Uses composite index: (account, created_at, status)
    $returnData = salsModel::whereIn('account', $shopIds)
        ->whereBetween('created_at', [$start_date, $end_date])
        ->where('status', 'Return')
        ->selectRaw('account,
            SUM(CASE WHEN transactionType = "Credit" THEN totalPrice ELSE 0 END) as credit_return_total,
            SUM(CASE WHEN transactionType = "Cash" THEN totalPrice ELSE 0 END) as cash_return_total')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Return data count: ' . $returnData->count());
     
    // 3. Get expenses (OPTIMIZED)
    // Uses composite index: (account, created_at) and covering index (account, amount)
    $expensesData = expensesModel::whereIn('account', $shopIds)
        ->whereBetween('created_at', [$start_date, $end_date])
        ->selectRaw('account, COALESCE(SUM(amount), 0) as total_expenses')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Expenses data count: ' . $expensesData->count());
     
    // 4. Get paid invoices (OPTIMIZED)
    // Uses composite index: (account, created_at)
    $paidInvoicesData = debtsModel::whereIn('account', $shopIds)
        ->whereBetween('created_at', [$start_date, $end_date])
        ->selectRaw('account,
            COALESCE(SUM(amount), 0) as total_paid_invoices,
            COALESCE(SUM(chip_amount), 0) as total_chip_used')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Paid invoices data count: ' . $paidInvoicesData->count());
     
    // 5. Get credit receivings (isPaid = 0) (OPTIMIZED)
    // Uses composite index: (account, isPaid, created_at) for fast filtering
    $creditReceivingsData = recevingModel::whereIn('account', $shopIds)
        ->where('isPaid', 0)
        ->where(function($q) {
            $q->whereNull('status')->orWhere('status', '!=', 'Returned');
        })
        ->whereBetween('created_at', [$start_date, $end_date])
        ->selectRaw('account,
            COALESCE(SUM(price * COALESCE(quantity, 0)), 0) as total_credit_receivings,
            COALESCE(SUM(COALESCE(quantity, 0)), 0) as credit_receiving_quantity')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Credit receivings data count: ' . $creditReceivingsData->count());
     
    // 6. Get paid receivings (isPaid = 1) (OPTIMIZED)
    // Uses composite index: (account, isPaid, created_at)
    $paidReceivingsData = recevingModel::whereIn('account', $shopIds)
        ->where('isPaid', 1)
        ->where(function($q) {
            $q->whereNull('status')->orWhere('status', '!=', 'Returned');
        })
        ->whereBetween('created_at', [$start_date, $end_date])
        ->selectRaw('account,
            COALESCE(SUM(price * COALESCE(quantity, 0)), 0) as total_paid_receivings,
            COALESCE(SUM(COALESCE(quantity, 0)), 0) as paid_receivings_quantity')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Paid receivings data count: ' . $paidReceivingsData->count());

    // 6.5. Get returned receivings (is_return = 1 or status = 'Returned') (OPTIMIZED)
    // Uses composite index: (account, is_return, created_at) for fast filtering
    $returnedReceivingsData = recevingModel::whereIn('account', $shopIds)
        ->where(function($q) {
            $q->where('is_return', 1)->orWhere('status', 'Returned');
        })
        ->whereBetween('created_at', [$start_date, $end_date])
        ->selectRaw('account, COALESCE(SUM(price * COALESCE(quantity, 0)), 0) as total_returned_receivings')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Returned receivings data count: ' . $returnedReceivingsData->count());

    // 7. Get supplier payments (OPTIMIZED)
    // Uses composite index: (account, created_at)
    $supplierPaymentsData = madeni::whereIn('account', $shopIds)
        ->whereBetween('created_at', [$start_date, $end_date])
        ->selectRaw('account, COALESCE(SUM(amount), 0) as total_supplier_payments')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Supplier payments data count: ' . $supplierPaymentsData->count());
     
    // 8. Get cash submissions (OPTIMIZED: removed DB::raw wrapper to use index)
    // report_date is a DATE column, so we convert the datetime range to date range
    $cashStartDate = date('Y-m-d', strtotime($start_date));
    $cashEndDate = date('Y-m-d', strtotime($end_date));
    
    $cashSubmissionsData = cashSubmitModel::whereIn('account', $shopIds)
        ->whereBetween('report_date', [$cashStartDate, $cashEndDate])
        ->selectRaw('account, COALESCE(SUM(submitted_cash), 0) as total_cash_submitted')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Cash submissions data count: ' . $cashSubmissionsData->count());
     
    // 9. Get total product quantities (OPTIMIZED)
    // Uses composite index: (account, created_at)
    $productQuantities = salsModel::whereIn('account', $shopIds)
        ->whereBetween('created_at', [$start_date, $end_date])
        ->selectRaw('account, SUM(pQuantity) as total_quantity')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Product quantities count: ' . $productQuantities->count());
     
    // 10. Get banking transfers (OPTIMIZED: ensure index on shop_id+transfer_date exists)
    $bankingData = BankingTransfer::whereIn('shop_id', $shopIds)
        ->whereBetween('transfer_date', [$start_date, $end_date])
        ->selectRaw('shop_id, COALESCE(SUM(amount), 0) as total_banking')
        ->groupBy('shop_id')
        ->get()
        ->keyBy('shop_id');
    \Log::info('AllShopReport: Banking transfers count: ' . $bankingData->count());
     
    // 11. Get banking chips (OPTIMIZED: ensure index on shop_id+transfer_date exists)
    $chipData = BankingChip::whereIn('shop_id', $shopIds)
        ->whereBetween('transfer_date', [$start_date, $end_date])
        ->selectRaw('shop_id, COALESCE(SUM(chip_amount), 0) as total_chip')
        ->groupBy('shop_id')
        ->get()
        ->keyBy('shop_id');
    \Log::info('AllShopReport: Banking chips count: ' . $chipData->count());
     
    // 12. Get offers (OPTIMIZED)
    // Uses composite index: (account, offered_items, created_at)
    $offersData = salsModel::whereIn('account', $shopIds)
        ->where('offered_items', true)
        ->whereBetween('created_at', [$start_date, $end_date])
        ->selectRaw('account, COALESCE(SUM(COALESCE(pQuantity, 0) * COALESCE(productPrice, 0)), 0) as total_offered')
        ->groupBy('account')
        ->get()
        ->keyBy('account');
    \Log::info('AllShopReport: Offers data count: ' . $offersData->count());
     
    // 13. Get transaction balances and discrepancies in batch (OPTIMIZED)
    // Pre-fetch all balances for the date range in a single query to avoid N+1
    $balanceDateStart = $usingDateRange ? $startDate : $reportDate->copy()->startOfDay();
    $balanceDateEnd = $usingDateRange ? $endDate : $reportDate->copy()->endOfDay();
    
    // Fetch all balances for all shops in the date range in one query
    $allBalances = \App\Models\TransactionBalance::whereIn('shop_id', $shopIds)
        ->whereBetween('balance_date', [
            $balanceDateStart->format('Y-m-d'),
            $balanceDateEnd->format('Y-m-d')
        ])
        ->get()
        ->keyBy('shop_id');
    \Log::info('AllShopReport: All balances count: ' . $allBalances->count());
    \Log::info('AllShopReport: Balance keys (shop_ids): ' . implode(', ', $allBalances->pluck('shop_id')->unique()->toArray()));
     
    $balances = [];
    foreach ($shopIds as $accountId) {
        // For date range, we want the latest balance within that range
        if ($usingDateRange) {
            // Get the balance for the end date specifically, or the most recent one
            $shopBalances = $allBalances->where('shop_id', $accountId);
            $targetDate = $endDate->toDateString();
            $balance = $shopBalances->firstWhere('balance_date', $targetDate);
            
            // If no balance exists for end date, use the most recent one in the range
            if (!$balance && $shopBalances->isNotEmpty()) {
                $balance = $shopBalances->sortByDesc('balance_date')->first();
            }
            
            // If still no balance, create a default structure
            if (!$balance) {
                $balances[$accountId] = [
                    'is_balanced' => true,
                    'cash_difference' => 0,
                    'issues' => [],
                    'expected_cash' => 0,
                ];
            } else {
                $balances[$accountId] = [
                    'is_balanced' => $balance->is_balanced,
                    'cash_difference' => $balance->cash_difference,
                    'issues' => [], // We'll populate discrepancies separately
                    'expected_cash' => $balance->expected_cash,
                ];
            }
        } else {
            // Single date: look for exact date match
            $targetDate = $reportDate->toDateString();
            $balance = $allBalances->firstWhere('shop_id', $accountId);
            
            if (!$balance || $balance->balance_date !== $targetDate) {
                $balances[$accountId] = [
                    'is_balanced' => true,
                    'cash_difference' => 0,
                    'issues' => [],
                    'expected_cash' => 0,
                ];
            } else {
                $balances[$accountId] = [
                    'is_balanced' => $balance->is_balanced,
                    'cash_difference' => $balance->cash_difference,
                    'issues' => [],
                    'expected_cash' => $balance->expected_cash,
                ];
            }
        }
    }
    \Log::info('AllShopReport: Balances array built for shops: ' . implode(', ', array_keys($balances)));
    
    // Fetch all discrepancies for all shops in a single query (OPTIMIZED)
    // Reuse the $allBalances collection already fetched above to get balance IDs
    $balanceIds = $allBalances->pluck('id')->toArray();
    
    // Get all discrepancies for those balances in one query
    $allDiscrepancies = \App\Models\TransactionDiscrepancy::whereIn('balance_id', $balanceIds)
        ->where('is_resolved', false)
        ->get();
    
    // Group discrepancies by shop_id using the $allBalances collection
    $discrepanciesByShop = $allDiscrepancies->groupBy(function($disc) use ($allBalances) {
        $balance = $allBalances->firstWhere('id', $disc->balance_id);
        return $balance ? $balance->shop_id : null;
    });
    \Log::info('AllShopReport: Discrepancies grouped by shop count: ' . $discrepanciesByShop->count());
    \Log::info('AllShopReport: Discrepancy shop IDs: ' . implode(', ', $discrepanciesByShop->keys()->toArray()));
    
    $discrepancyCounts = [];
    foreach ($shopIds as $accountId) {
        $discrepancyCounts[$accountId] = $discrepanciesByShop->get($accountId, collect())->count();
        
        // Add discrepancy details to balance issues if any exist
        if (isset($balances[$accountId]) && $discrepanciesByShop->has($accountId)) {
            $issues = [];
            foreach ($discrepanciesByShop[$accountId] as $disc) {
                $issues[] = $disc->description;
            }
            $balances[$accountId]['issues'] = $issues;
        }
    }
    \Log::info('AllShopReport: Discrepancy counts per shop: ' . json_encode($discrepancyCounts));
    
    // Build reports
    $shopReports = collect();
    
    foreach ($allShops as $shop) {
        \Log::info('AllShopReport: Processing shop ID=' . $shop->id . ', name=' . $shop->name);
        $accountId = $shop->id;
        
        $sales = $salesData[$accountId] ?? null;
        $returns = $returnData[$accountId] ?? null;
        $expense = $expensesData[$accountId] ?? null;
        $paidInvoice = $paidInvoicesData[$accountId] ?? null;
        $creditReceiving = $creditReceivingsData[$accountId] ?? null;
        $paidReceiving = $paidReceivingsData[$accountId] ?? null;
        $returnedReceiving = $returnedReceivingsData[$accountId] ?? null;
        $supplierPayment = $supplierPaymentsData[$accountId] ?? null;
        $cashSubmission = $cashSubmissionsData[$accountId] ?? null;
        $productQty = $productQuantities[$accountId] ?? null;
        $banking = $bankingData[$accountId] ?? null;
        $chip = $chipData[$accountId] ?? null;
        $offer = $offersData[$accountId] ?? null;
        
        \Log::info('AllShopReport: Shop ' . $accountId . ' - sales=' . ($sales ? 'found' : 'null') . ', returns=' . ($returns ? 'found' : 'null') . ', expense=' . ($expense ? 'found' : 'null'));
        
        $totalCashSales = (($sales?->cash_sales_paid ?? 0) + ($sales?->cash_sales_partial ?? 0)) - ($returns?->cash_return_total ?? 0);
        $totalCreditSales = (($sales?->total_credit ?? 0) + ($sales?->partial_credit ?? 0)) - ($returns?->credit_return_total ?? 0);
        $totalSales = $totalCashSales + $totalCreditSales;
        $totalReturn = ($returns?->credit_return_total ?? 0) + ($returns?->cash_return_total ?? 0);
        
        $cashReturns = $sales?->cash_returns ?? 0;
        $creditReturns = $sales?->credit_returns ?? 0;
        
        $netCashSales = $totalCashSales - $cashReturns;
        
        $profit = (($sales?->cash_sales_paid ?? 0) + ($sales?->cash_sales_partial ?? 0)) - ($expense?->total_expenses ?? 0) - ($paidReceiving?->total_paid_receivings ?? 0);
        
        $cashAmount = $netCashSales + ($chip?->total_chip ?? 0)
                    + ($paidInvoice?->total_paid_invoices ?? 0)
                    - ($expense?->total_expenses ?? 0)
                    - ($paidReceiving?->total_paid_receivings ?? 0)
                    - ($supplierPayment?->total_supplier_payments ?? 0)
                    - ($paidInvoice?->total_chip_used ?? 0);
        
        $cashSubmit = ($cashSubmission?->total_cash_submitted ?? 0) + ($chip?->total_chip ?? 0);
        $bankingAmount = $banking?->total_banking ?? 0;

        $crReceiving = ($creditReceiving?->total_credit_receivings ?? 0) - ($returnedReceiving->total_returned_receivings ?? 0) ;

        $bankDifference = $cashSubmit - $bankingAmount;
        $cashDifference = $cashAmount - ($cashSubmission?->total_cash_submitted ?? 0);
        
        $sellingWorth = $sales?->total_sales ?? 0;
        $costWorth = (($paidReceiving?->total_paid_receivings ?? 0) + ($crReceiving))
                    - $totalSales
                    - ($sales?->total_discount ?? 0)
                    - ($offer?->total_offered ?? 0);
        
        $balance = $balances[$accountId] ?? [];
        $isBalanced = $balance['is_balanced'] ?? (abs($cashDifference) <= 0.01);
        $balanceIssues = $balance['issues'] ?? [];
        
        // Add sales balance check
        $calculatedTotal = $totalCashSales + $totalCreditSales;
        if (abs($calculatedTotal - $totalSales) > 0.01) {
            $balanceIssues[] = 'Sales mismatch: Cash (' . number_format($totalCashSales) . ') + Credit (' . number_format($totalCreditSales) . ') ≠ Total (' . number_format($totalSales) . ')';
        }
        
        $shopReports->push((object)[
            'shop_id' => $accountId,
            'shop_name' => $shop->name ?? 'Unnamed Shop',
            'location' => $shop->location ?? 'N/A',
            'total_transactions' => $sales->total_transactions ?? 0,
            'total_product_quantity' => $productQty->total_quantity ?? 0,
            'cash_sales' => $totalCashSales,
            'credit_sales' => $totalCreditSales,
            'total_sales' => $totalSales,
            'total_return' => $totalReturn,
            'total_bank' => $bankingAmount,
            'bank_diff' => $bankDifference,
            'total_offer' => $offer->total_offered ?? 0,
            'credit_return' => -$creditReturns,
            'discount' => $sales->total_discount ?? 0,
            'discount_increase' => $sales->total_discount_increase ?? 0,
            'expenses' => $expense->total_expenses ?? 0,
            'paid_invoices' => $paidInvoice->total_paid_invoices ?? 0,
            'chip_used' => $paidInvoice->total_chip_used ?? 0,
            'profit' => $profit,
            'cash_receivings' => $paidReceiving->total_paid_receivings ?? 0,
            'credit_receivings' => $crReceiving,
            'returned_receivings' => $returnedReceiving->total_returned_receivings ?? 0,
            'credit_receivings_quantity' => $creditReceiving->credit_receiving_quantity ?? 0,
            'paid_receivings' => $supplierPayment->total_supplier_payments ?? 0,
            'cash_amount' => $cashAmount,
            'cash_submitted' => $cashSubmit,
            'totalChip' => $chip->total_chip ?? 0,
            'cash_difference' => $cashDifference,
            'expected_cash' => $cashAmount,
            'debt' => $sales->total_debt ?? 0,
            'has_sales' => ($sales->total_transactions ?? 0) > 0,
            'cost_worth' => $costWorth,
            'selling_worth' => $sellingWorth,
            'is_balanced' => $isBalanced,
            'balance_issues' => $balanceIssues,
            'discrepancy_count' => $discrepancyCounts[$accountId] ?? 0,
        ]);
        
        \Log::info('AllShopReport: Shop ' . $accountId . ' report built - total_sales=' . ($shop->total_sales ?? 0) . ', has_sales=' . ($shop->has_sales ?? false));
    }
    
    // Calculate totals (same as before)
    $totals = (object)[
        'total_transactions'        => $shopReports->sum('total_transactions'),
        'total_product_quantity'    => $shopReports->sum('total_product_quantity'),
        'cash_sales'                => $shopReports->sum('cash_sales'),
        'credit_sales'              => $shopReports->sum('credit_sales'),
        'total_sales'               => $shopReports->sum('total_sales'),
        'total_return'              => $shopReports->sum('total_return'),
        'cash_return'               => $shopReports->sum('cash_return'),
        'credit_return'             => $shopReports->sum('credit_return'),
        'total_bank'                => $shopReports->sum('total_bank'),
        'bank_diff'                 => $shopReports->sum('bank_diff'),
        'offer'                     => $shopReports->sum('total_offer'),
        'discount'                  => $shopReports->sum('discount'),
        'discount_increase'         => $shopReports->sum('discount_increase'),
        'expenses'                  => $shopReports->sum('expenses'),
        'paid_invoices'             => $shopReports->sum('paid_invoices'),
        'chip_used'                 => $shopReports->sum('chip_used'),
        'profit'                    => $shopReports->sum('profit'),
        'cash_receivings'           => $shopReports->sum('cash_receivings'),
        'credit_receivings'         => $shopReports->sum('credit_receivings'),
        'returned_receivings'       => $shopReports->sum('returned_receivings'),
        'paid_receivings'           => $shopReports->sum('paid_receivings'),
        'cash_amount'               => $shopReports->sum('cash_amount'),
        'cash_submitted'            => $shopReports->sum('cash_submitted'),
        'totalChip'                 => $shopReports->sum('totalChip'),
        'debt'                      => $shopReports->sum('debt'),
        'cost_worth'                => $shopReports->sum('cost_worth'),
        'selling_worth'             => $shopReports->sum('selling_worth'),
        'total_discrepancies'       => $shopReports->sum('discrepancy_count'),
    ];
    
    $activeShopsCount = $allShops->count();
    $shopsWithSalesCount = $shopReports->where('has_sales', true)->count();
    $report = $shopReports;
    
    \Log::info('AllShopReport: Final shopReports count: ' . $shopReports->count());
    \Log::info('AllShopReport: Has sales count: ' . $shopsWithSalesCount);
    
    if ($shopReports->isEmpty()) {
        \Log::warning('AllShopReport: shopReports is EMPTY after processing all shops!');
    } else {
        \Log::info('AllShopReport: First shop report: ', (array) $shopReports->first());
    }
    
    $data = compact(
        'shopReports',
        'dateParam',
        'totals',
        'activeShopsCount',
        'shopsWithSalesCount',
        'report',
        'allShops',
        'dateFrom',
        'dateTo'
    );
    
    if (strtolower(trim($user->levelStatus)) === 'admin') {
        \Log::info('AllShopReport: Returning admin view with shopReports count: ' . $shopReports->count());
        return view('admin.shopReport', $data);
    }
    if(!empty($user->levelStatus)) {
        \Log::info('AllShopReport: Returning user view with shopReports count: ' . $shopReports->count());
        return view('user.shopReport', $data);
    }
    
    \Log::warning('AllShopReport: No view returned - user levelStatus is empty');
}

    /**
     * Get users from all accessible accounts
     * Used in makeReceiving to show which staff can receive from suppliers
     * Shows all users from all shops that the current user has access to
     */
    public function getSupplierUsers(Request $req)
    {
        $user = Auth::user();
        
        // Determine which accounts to query based on user role
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admin sees all users from all accounts
            $accountIds = accountModel::pluck('id')->toArray();
        } else {
            // Regular user sees users from their assigned accounts
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            
            // Also include the user's main account field
            if ($user->account) {
                $accountIds[] = $user->account;
            }
            $accountIds = array_unique($accountIds);
        }
        
        if (empty($accountIds)) {
            return response()->json(['success' => false, 'message' => 'No accessible accounts found']);
        }
        
        // Get all non-admin users from these accounts
        $users = DB::table('users')
            ->where(function($query) use ($accountIds) {
                $query->whereIn('account', $accountIds)
                      ->orWhereIn('id', function($q) use ($accountIds) {
                          $q->select('user_id')
                            ->from('user_accounts')
                            ->whereIn('account', $accountIds);
                      });
            })
            ->where(function($query) {
                $query->where('levelStatus', '!=', 'Admin')
                      ->where('levelStatus', '!=', 'admin');
            })
            ->select('id', 'name', 'levelStatus')
            ->distinct()
            ->get();
            
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    /**
     * Get suppliers (vendors) from all accessible accounts
     * Used in makeReceiving to show which suppliers are available
     * Shows all suppliers from all shops that the current user has access to
     */
    public function getUserSuppliers(Request $req)
    {
        $user = Auth::user();
        
        // Determine which accounts to query based on user role
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admin sees all suppliers from all accounts
            $accountIds = accountModel::pluck('id')->toArray();
        } else {
            // Regular user sees suppliers from their assigned accounts
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            
            // Also include the user's main account field
            if ($user->account) {
                $accountIds[] = $user->account;
            }
            $accountIds = array_unique($accountIds);
        }
        
        if (empty($accountIds)) {
            return response()->json(['success' => false, 'message' => 'No accessible accounts found']);
        }
        
        // Get all suppliers (vendors) from these accounts
        $suppliers = DB::table('vendors')
            ->whereIn('account', $accountIds)
            ->select('id', 'name')
            ->get();
            
        return response()->json([
            'success' => true,
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Check today's balance for the user's shop(s)
     * Returns JSON with balance status and details
     */
    public function checkTodayBalance(Request $req)
    {
        $user = Auth::user();
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        
        // Determine which accounts to query
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $accountIds = accountModel::pluck('id')->toArray();
        } else {
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
        }
        
        if (empty($accountIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No shops assigned to your account',
                'balanced' => false
            ]);
        }
        
        $results = [];
        $allBalanced = true;
        $totalCashSales = 0;
        $totalCreditSales = 0;
        $totalSales = 0;
        $totalCashAmount = 0;
        $totalCashSubmitted = 0;
        
        foreach ($accountIds as $accountId) {
            // Get shop name
            $shop = accountModel::where('id', $accountId)->first();
            $shopName = $shop ? $shop->name : 'Unknown Shop';
            
            // Sales aggregates for today
            $salesAgg = salsModel::where('account', $accountId)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->where(function($q) { $q->where('salesName', '!=', '')->orWhereNull('salesName'); })
                ->selectRaw('
                    SUM(totalPrice) as total_sales,
                    SUM(COALESCE(return_amount, 0)) as total_return,
                    SUM(CASE WHEN status = "Paid" THEN paid WHEN status = "Partial" THEN paid ELSE 0 END) as cash_sales,
                    SUM(CASE WHEN status = "Debt" THEN credit WHEN status = "Partial" THEN credit ELSE 0 END) as credit_sales,
                    SUM(discount) as total_discount,
                    SUM(discount_increase) as total_discount_increase
                ')
                ->first();
            
            $cashSales = $salesAgg->cash_sales ?? 0;
            $creditSales = $salesAgg->credit_sales ?? 0;
            $totalShopSales = $salesAgg->total_sales ?? 0;
            $discount = $salesAgg->total_discount ?? 0;
            $discountIncrease = $salesAgg->total_discount_increase ?? 0;
            
            // Cash returns (from sales with status Return and transactionType Cash)
            $cashReturns = salsModel::where('account', $accountId)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->where('status', 'Return')
                ->where('transactionType', 'Cash')
                ->sum('totalPrice');
            
            // Expenses
            $expenses = expensesModel::where('account', $accountId)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
            
            // Paid invoices (debts)
            $paidInvoices = debtsModel::where('account', $accountId)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
            
            // Paid receivings (isPaid = 1)
            $paidReceivings = recevingModel::where('account', $accountId)
                ->where('isPaid', 1)
                ->where(function($q) { $q->whereNull('status')->orWhere('status', '!=', 'Returned'); })
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum(DB::raw('price * COALESCE(quantity, 0)'));
            
            // Supplier payments (madeni)
            $supplierPayments = madeni::where('account', $accountId)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
            
            // Cash submitted
            $cashSubmitted = cashSubmitModel::where('account', $accountId)
                ->whereDate('report_date', now()->format('Y-m-d'))
                ->sum('submitted_cash');
            
            // Calculate cash amount
            $netCashSales = $cashSales - $cashReturns;
            $cashAmount = $netCashSales
                        + $paidInvoices
                        + $discountIncrease
                        - $discount
                        - $expenses
                        - $paidReceivings;
            
            // Check balances
            $salesBalanced = abs(($cashSales + $creditSales) - $totalShopSales) < 0.01;
            $cashBalanced = abs($cashAmount - $cashSubmitted) < 0.01;
            $isBalanced = $salesBalanced && $cashBalanced;
            
            if (!$isBalanced) {
                $allBalanced = false;
            }
            
            $issues = [];
            if (!$salesBalanced) {
                $issues[] = 'Sales mismatch: Cash ' . number_format($cashSales) . ' + Credit ' . number_format($creditSales) . ' ≠ Total ' . number_format($totalShopSales);
            }
            if (!$cashBalanced) {
                $issues[] = 'Cash: expected ' . number_format($cashAmount) . ', submitted ' . number_format($cashSubmitted);
            }
            
            $results[] = [
                'shop_id' => $accountId,
                'shop_name' => $shopName,
                'cash_sales' => round($cashSales, 2),
                'credit_sales' => round($creditSales, 2),
                'total_sales' => round($totalShopSales, 2),
                'cash_amount' => round($cashAmount, 2),
                'cash_submitted' => round($cashSubmitted, 2),
                'sales_balanced' => $salesBalanced,
                'cash_balanced' => $cashBalanced,
                'is_balanced' => $isBalanced,
                'issues' => $issues,
            ];
            
            $totalCashSales += $cashSales;
            $totalCreditSales += $creditSales;
            $totalSales += $totalShopSales;
            $totalCashAmount += $cashAmount;
            $totalCashSubmitted += $cashSubmitted;
        }
        
        // Overall totals balance
        $overallSalesBalanced = abs(($totalCashSales + $totalCreditSales) - $totalSales) < 0.01;
        $overallCashBalanced = abs($totalCashAmount - $totalCashSubmitted) < 0.01;
        $overallBalanced = $overallSalesBalanced && $overallCashBalanced && $allBalanced;
        
        return response()->json([
            'success' => true,
            'date' => now()->format('Y-m-d'),
            'overall_balanced' => $overallBalanced,
            'message' => $overallBalanced ? "Today's work is good! All balances match." : 'Some shops have balance issues',
            'summary' => [
                'cash_sales' => round($totalCashSales, 2),
                'credit_sales' => round($totalCreditSales, 2),
                'total_sales' => round($totalSales, 2),
                'cash_amount' => round($totalCashAmount, 2),
                'cash_submitted' => round($totalCashSubmitted, 2),
                'sales_balanced' => $overallSalesBalanced,
                'cash_balanced' => $overallCashBalanced,
            ],
            'shops' => $results
        ]);
    }
}
