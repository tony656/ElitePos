<?php

namespace App\Http\Controllers;

use App\Models\adsModel;
use Illuminate\Http\Request;
use App\Models\productsModel;
use App\Models\usersModel;
use App\Models\expensesModel;
use App\Models\ordersModel;
use App\Models\systemModel;
use App\Models\salsModel;
use App\Models\recevingModel;
use Illuminate\Support\Facades\DB;
use App\Models\notifications;
use Illuminate\Support\Facades\Auth;
use function getSessionAccountId;

class homeController extends Controller
{
    //
    
 public function dashboard() {
    $user = Auth::user();
    $Account = getSessionAccountId();
    
    // Current month sales
    $currentMonthStart = now()->startOfMonth();
    $currentMonthEnd = now()->endOfMonth();
    
    \Log::info('Dashboard ads query', [
        'user_id' => $user->id ?? null,
        'account' => $Account ?? null
    ]);

    $ads = adsModel::orderBy('created_at', 'desc')
            ->get();

    \Log::info('Dashboard ads result', [
        'ads_count' => $ads->count(),
        'ads_data' => $ads->toArray()
    ]);

    $currentMonthSales = salsModel::where('account', $Account)
        ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
        ->sum('totalPrice');

    // Last month sales
    $lastMonthStart = now()->subMonth()->startOfMonth();
    $lastMonthEnd = now()->subMonth()->endOfMonth();
    
    $lastMonthSales = salsModel::where('account', $Account)
        ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
        ->sum('totalPrice');
    
    // Get daily sales for current month
    $currentMonthDailySales = [];
    $lastMonthDailySales = [];
    
    $daysInCurrentMonth = now()->daysInMonth;
    $daysInLastMonth = now()->subMonth()->daysInMonth;
    
    for ($day = 1; $day <= 31; $day++) {
        if ($day <= $daysInCurrentMonth) {
            $dayStart = now()->startOfMonth()->addDays($day - 1)->startOfDay();
            $dayEnd = now()->startOfMonth()->addDays($day - 1)->endOfDay();
            
            $currentMonthDailySales[$day] = salsModel::where('account', $Account)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('totalPrice');
        } else {
            $currentMonthDailySales[$day] = 0;
        }
        
        if ($day <= $daysInLastMonth) {
            $lastMonthDayStart = now()->subMonth()->startOfMonth()->addDays($day - 1)->startOfDay();
            $lastMonthDayEnd = now()->subMonth()->startOfMonth()->addDays($day - 1)->endOfDay();
            
            $lastMonthDailySales[$day] = salsModel::where('account', $Account)
                ->whereBetween('created_at', [$lastMonthDayStart, $lastMonthDayEnd])
                ->sum('totalPrice');
        } else {
            $lastMonthDailySales[$day] = 0;
        }
    }

    $TProducts = productsModel::where('account', $Account)->count();

    $ofs = productsModel::where('quantity', '<', 1)
        ->where('account', $Account)
        ->count();
    
    $users = usersModel::where('account', $Account)->count();
    $revenue = expensesModel::where('account', $Account)->count();
    $revenueAmount = expensesModel::where('account', $Account)->sum('amount');
    
    $orders = salsModel::where('account', $Account)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
    $totalOrders = salsModel::where('account', $Account)->count();
    
    // Daily calculations for today
    $todayStart = now()->startOfDay();
    $todayEnd = now()->endOfDay();
    
    // Total items received today (quantity) and total cost
    $todayReceivedItems = recevingModel::where('account', $Account)
        ->whereBetween('created_at', [$todayStart, $todayEnd])
        ->where('is_return', '!=', 1)
        ->sum('quantity');
    
    $todayReceivedCost = recevingModel::where('account', $Account)
        ->whereBetween('created_at', [$todayStart, $todayEnd])
        ->where('is_return', '!=', 1)
        ->selectRaw('SUM(price * quantity) as total_cost')
        ->first()
        ->total_cost ?? 0;
    
    // Total items sold today (quantity) and total revenue
    $todaySoldItems = salsModel::where('account', $Account)
        ->whereBetween('created_at', [$todayStart, $todayEnd])
        ->sum('pQuantity');
    
    $todaySalesRevenue = salsModel::where('account', $Account)
        ->whereBetween('created_at', [$todayStart, $todayEnd])
        ->sum('totalPrice');
    
    $getName = systemModel::where('account', $Account)->first();
    $status = session('status');
    
    $dataNotifications = notifications::where('account', $Account)
        ->where('head', '=', $status)
        ->orderBy('id', 'DESC')
        ->take(3)
        ->get();

    $Mstart_date = date("Y-01-01") . ' 00:00:00';
    $Mend_date = date("Y-12-31") . ' 23:59:59';

    $Msale = salsModel::where('account', $Account)
        ->whereBetween('created_at', [$Mstart_date, $Mend_date])
        ->sum('totalPrice');

    $Ysale = salsModel::where('account', $Account)
        ->whereBetween('created_at', [$Mstart_date, $Mend_date])
        ->sum('totalPrice');
    
    $lastYearStart = date("Y-01-01", strtotime("-1 year")) . ' 00:00:00';
    $lastYearEnd = date("Y-12-31", strtotime("-1 year")) . ' 23:59:59';

    $additionalExpenses = expensesModel::where('account', $Account)
        ->whereBetween('created_at', [$Mstart_date, $Mend_date])
        ->sum('amount');

    $sumBuyingPrice = salsModel::where('sales.account', $Account)
        ->join('products', 'sales.productId', '=', 'products.product_id')
        ->whereBetween('sales.created_at', [$Mstart_date, $Mend_date])
        ->sum('products.bPrice');

    $NetProfit = $Msale - $sumBuyingPrice - $additionalExpenses;

    $LadditionalExpenses = expensesModel::where('account', $Account)
        ->whereBetween('created_at', [$lastYearStart, $lastYearEnd])
        ->sum('amount');

    $LsumBuyingPrice = salsModel::where('sales.account', $Account)
        ->join('products', 'sales.productId', '=', 'products.product_id')
        ->whereBetween('sales.created_at', [$lastYearStart, $lastYearEnd])
        ->sum('products.bPrice');

    $MrevenueAmount = expensesModel::where('account', $Account)
        ->whereBetween('created_at', [$Mstart_date, $Mend_date])
        ->sum('amount');

    $LNetProfit = $Ysale - $LsumBuyingPrice - $LadditionalExpenses;

    // Monthly sales data
    $months = salsModel::select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('SUM(totalPrice) as totalPrice')
    )
    ->where('sales.account', $Account)
    ->whereYear('created_at', date('Y'))
    ->groupBy(DB::raw('MONTH(created_at)'))
    ->get()
    ->map(function ($month) {
        return [
            'month' => (int)$month->month,
            'totalPrice' => (float)$month->totalPrice,
        ];
    })
    ->toArray();

    // Initialize array for 12 months
    $monthlyTotalPrices = array_fill(0, 12, 0);

    // Fill in the totals
    foreach ($months as $month) {
        $monthlyTotalPrices[$month['month'] - 1] = $month['totalPrice'];
    }

    // Calculate growth percentage
    $growthPercentage = 0;
    if ($lastMonthSales > 0) {
        $growthPercentage = (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
    } elseif ($currentMonthSales > 0) {
        $growthPercentage = 100;
    }

    // Prepare data for view
    $data = compact(
        'TProducts', 'ofs', 'users', 'revenue', 'totalOrders', 'orders',
        'NetProfit', 'LNetProfit', 'getName', 'monthlyTotalPrices', 'MrevenueAmount',
        'revenueAmount', 'dataNotifications', 'Msale',
        'currentMonthSales', 'lastMonthSales',
        'currentMonthDailySales', 'lastMonthDailySales',
        'growthPercentage','ads',
        'todayReceivedItems', 'todayReceivedCost', 'todaySoldItems', 'todaySalesRevenue'
    );

    // Use case-insensitive comparison for role checking
    $role = strtolower(trim($user->levelStatus));
    
    if ($role === 'admin') {
        return view('admin.home', $data);
    }
    
    // For non-admin users (Manager, Seller, etc.)
    if (!empty($user->levelStatus)) {
        return view('user.home', $data);
    }
    
    // If somehow levelStatus is empty, abort with unauthorized
    abort(403, 'Unauthorized access');
}
}
