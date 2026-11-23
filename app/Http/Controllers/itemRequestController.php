<?php

namespace App\Http\Controllers;

use App\Models\itemRequestModel;
use Illuminate\Http\Request;
use App\Models\logModal;
use App\Models\productsModel;
use Illuminate\Support\Facades\DB;
use App\Models\recevingModel;
use App\Models\salsModel;
use  App\Models\usersModel;
use App\Models\accountModel;
use App\Models\UserAccount;
use App\Models\madeni;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use function getCurrentShopId;
use function getUserAccounts;
class itemRequestController extends Controller
{
    public function index() {
        if (!canUser('item_request')) {
        abort(403, 'Unauthorized access');
    }
        $isAdmin = in_array(strtolower(trim(Auth::user()->levelStatus)), ['admin', 'admin2']);

        $products = productsModel::where('account', getCurrentShopId())->get();

        $orders = itemRequestModel::where('account', getCurrentShopId())
            ->where('status', 'Pending')
            ->orderBy('id', 'desc')
            ->first();
        
        $carts = itemRequestModel::where('account', getCurrentShopId())
            ->where('status', 'Pending')
            ->where('requestName', $orders->requestName ?? '')
            ->orderBy('id', 'desc')
            ->get();

        $supplierName = accountModel::where('id', $orders->supplierId ?? '')->value('name');
        $Allocation = usersModel::where('id', $orders->assigned_to ?? '')->value('name');
        $payment_type = $orders->payment_type ?? '';
        $shops = getUserAccounts();
        $shopIds = array_column($shops, 'id');

        // ── Suppliers (Customers / Accounts) ──────────────────────────────────
        // Admin: see all accounts except the current session account
        // User:  see only the accounts they are assigned to via user_accounts

                $Customers = DB::table('accounts')->whereIn('id', $shopIds)->get();
 
                $accessibleUserIds = DB::table('user_accounts')
                    ->whereIn('account', $shopIds)
                    ->pluck('user_id')
                    ->unique()
                    ->toArray();

                if (empty($accessibleUserIds)) {
                    $users = DB::table('users')->where('id', '=', 0)->get();
                } else {
                    $users = DB::table('users')
                        ->whereIn('id', $accessibleUserIds)
                        ->where('levelStatus', '!=', 'Admin')
                        ->orderBy('name', 'asc')
                        ->get();
                }
            


        $data = compact('products', 'orders', 'Customers','supplierName','Allocation','payment_type','carts', 'users', 'shops');

            return view('item_request', $data);
   
    }

    /**
     * Main Store Report
     * Tracks all items requested from Main Store (account 7) by other shops.
     */public function mainStoreReport(Request $req)
{
    $user = Auth::user();
    if (strtolower(trim($user->levelStatus)) !== 'admin' && !canUser('view_main_store_report')) {
        return redirect()->back()->with('error', 'Unauthorized access');
    }

    $dateFrom = $req->input('date_from', date('Y-m-d'));
    $dateTo = $req->input('date_to', date('Y-m-d'));
    $mainStoreId = 7;

    // 1. Get all requests from Main Store
    $allRequests = itemRequestModel::where('supplierId', $mainStoreId)
        ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
        ->orderBy('created_at', 'desc')
        ->get();

    // 2. Get shop names from accounts table
    $shopIds = $allRequests->pluck('account')->unique();
    $shopNames = DB::table('accounts')->whereIn('id', $shopIds)->pluck('name', 'id');

    // 3. Prepare report data grouped by date and shop
    $reportRows = [];
    $groupedRequests = [];

    // Group requests by date and shop
    foreach ($allRequests as $request) {
        $date = date('Y-m-d', strtotime($request->created_at));
        $key = $date . '_' . $request->account;
        
        if (!isset($groupedRequests[$key])) {
            $groupedRequests[$key] = [];
        }
        $groupedRequests[$key][] = $request;
    }

    // 4. Process each group
    foreach ($groupedRequests as $key => $requests) {
        $firstRequest = $requests[0];
        $shopId = $firstRequest->account;
        $date = date('Y-m-d', strtotime($firstRequest->created_at));
        $requestName = $firstRequest->requestName;

        // Calculate request totals
        $totalQty = 0;
        $totalValue = 0;
        $approvedQty = 0;
        $approvedValue = 0;
        $creditValue = 0;
        $cashValue = 0;
        $pendingValue = 0;
        $isReturn = 0;

        foreach ($requests as $request) {
            $totalQty += $request->quantity;
            $totalValue += $request->totalPrice;
            
            if ($request->is_return == 1) {
                $isReturn = 1;
            }
            
            if ($request->status == 'Approved') {
                $approvedQty += $request->quantity;
                $approvedValue += $request->totalPrice;
                
                if ($request->payment_type == 'credit') {
                    $creditValue += $request->totalPrice;
                } elseif ($request->payment_type == 'cash') {
                    $cashValue += $request->totalPrice;
                }
            } elseif ($request->status == 'Submitted') {
                $pendingValue += $request->totalPrice;
            }
        }

        // 5. Get receiving information
        $receiving = DB::table('receivings')
            ->where('receivingName', $requestName)
            ->first();

        $receivingId = null;
        if ($receiving) {
            $receivingId = $receiving->receivingId;
        }

        // 6. Get paid amount from madeni table (ONLY for this specific shop)
        $paidAmount = 0;
        
            // If we have a receiving ID, get payments for this shop and receiving
            $paidAmount = DB::table('madeni')
                ->where('account', $shopId)  // CRITICAL: Filter by shop
                ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->sum('amount');
        
        
        // If no receiving found, try using requestName directly
        if ($paidAmount == 0 && $requestName) {
            $paidAmount = DB::table('madeni')
                ->where('receivingsId', $requestName)
                ->where('account', $shopId)  // CRITICAL: Filter by shop
                ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->sum('amount');
        }

        // 7. Get approved receivings for this shop and date
        $approvedReceiving = DB::table('receivings')
            ->where('status', 'Approved')
            ->where('is_return', '!=', 1)
            ->where('supplier', 7)
            ->where('account', $shopId)
            ->whereDate('created_at', $date)
            ->selectRaw('SUM(quantity) as total_qty, SUM(price * quantity) as total_value')
            ->first();

        $approvedReceivingQty = $approvedReceiving ? $approvedReceiving->total_qty : 0;
        $approvedReceivingValue = $approvedReceiving ? $approvedReceiving->total_value : 0;

        // 8. Get returned value for this shop and date
        $returnedValue = DB::table('receivings')
            ->where('is_return', 1)
            ->where('account', $shopId)
            ->whereDate('created_at', $date)
            ->get()
            ->sum(function($item) {
                return $item->sellingPrice * $item->quantity;
            });

        // 9. Get sales data for this shop
        $salesData = DB::table('sales')
            ->where('account', $shopId)
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->get();

        $totalSales = $salesData->sum('totalPrice');
        $totalItemsSold = $salesData->sum('pQuantity');
        $cashSales = $salesData->where('status', 'Paid')->sum('totalPrice');
        $creditSales = $salesData->where('status', 'Debt')->sum('totalPrice');
        $totalDiscount = $salesData->sum('discount');

        // 10. Get daily sales for this specific shop and date
        $dailySales = DB::table('sales')
            ->where('account', $shopId)
            ->whereDate('created_at', $date)
            ->sum('totalPrice');

        // 11. Build the report row
        $reportRows[] = (object)[
            'date' => $date,
            'shop_id' => $shopId,
            'shop_name' => $shopNames[$shopId] ?? 'Unknown Shop',
            'request_name' => $requestName,
            'receiving_id' => $receivingId,
            'is_return' => $isReturn,
            
            // Request totals
            'total_qty' => $totalQty,
            'total_value' => $totalValue,
            'approved_request_qty' => $approvedQty,
            'approved_request_value' => $approvedValue,
            'credit_value' => $creditValue,
            'cash_value' => $cashValue,
            'pending_value' => $pendingValue,
            
            // Receiving totals
            'approved_receiving_qty' => $approvedReceivingQty,
            'approved_receiving_value' => $approvedReceivingValue,
            'approved_qty_diff' => max(0, $approvedQty - $approvedReceivingQty),
            'approved_value_diff' => max(0, $approvedValue - $approvedReceivingValue),
            'returned_value' => $returnedValue,
            
            // Payment
            'paidcash_value' => $paidAmount,
            
            // Sales data
            'shop_total_sales' => $totalSales,
            'shop_total_items_sold' => $totalItemsSold,
            'shop_cash_sales' => $cashSales,
            'shop_credit_sales' => $creditSales,
            'shop_discount' => $totalDiscount,
            'daily_sales' => $dailySales,
            
            // Original requests for reference
            'items' => $requests,
        ];
    }

    // 12. Sort by date descending
    usort($reportRows, function($a, $b) {
        return strtotime($b->date) - strtotime($a->date);
    });

    // 13. Calculate grand totals
    $grandTotals = (object)[
        'value' => $allRequests->sum('totalPrice'),
        'approved' => $allRequests->where('status', 'Approved')->sum('totalPrice'),
        'credit' => $allRequests->where('payment_type', 'credit')->sum('totalPrice'),
        'total_sales' => 0,
        'total_items_sold' => 0,
    ];

    // Calculate sales totals from shopSales
    foreach ($shopIds as $shopId) {
        $shopTotal = DB::table('sales')
            ->where('account', $shopId)
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->get();

        $grandTotals->total_sales += $shopTotal->sum('totalPrice');
        $grandTotals->total_items_sold += $shopTotal->sum('pQuantity');
    }

    return view('mainStoreReport', compact('reportRows', 'grandTotals', 'dateFrom', 'dateTo'));
}

 public function payInterShopRequest(Request $request) {
        $shopId = $request->input('shop_id');
        $date = $request->input('date');
        
        // Update item requests
        $updated = itemRequestModel::where('account', $shopId)
            ->where('supplierId', 7) // Main Store
            ->where('payment_type', 'credit')
            ->whereDate('created_at', $date)
            ->update(['payment_type' => 'cash']);
            
        if ($updated) {
            // Also update the receiving records for the shop so their report balances
            recevingModel::where('account', $shopId)
                ->where('supplier', 7)
                ->where('isDebt', 1)
                ->whereDate('created_at', $date)
                ->update(['isDebt' => 0, 'isPaid' => 1]);

            logModal::create([
                'title' => 'Inter-shop Payment',
                'description' => "Credit for shop #$shopId on $date marked as paid by " . Auth::user()->name,
            ]);
            return redirect()->back()->with('success', 'Credit marked as paid successfully');
        }
        return redirect()->back()->with('error', 'No credit found to pay');
    }

  public function viewRequest(Request $request) {
    if (!canUser('view_item_requests')) {
        abort(403, 'Unauthorized access');
    }
    $user = Auth::user();

    // ── Filter inputs ─────────────────────────────────────────────────────
    // Get the actual values from request
    $dateFrom   = $request->query('date_from', date('Y-m-d'));  // This gets the actual date string
    $dateTo     = $request->query('date_to', date('Y-m-d'));    // This gets the actual date string
    $shopFilter = $request->query('shop');       // This gets the actual shop ID
    $userAccounts = array_column(getUserAccounts(), 'id');
    
    if(!empty($shopFilter)) {
        session([
        'selected_shop_id' => $shopFilter,
    ]);
    }
     /*$itemsr = itemRequestModel::where('created_at', null)->orderBy('id', 'desc')->get();

     foreach($itemsr as $items) {

     $items->created_at = $items->updated_at;
     $items->save();

     } */
    $account = getCurrentShopId(); // This should be your current logged-in shop

    // Base query: requests where this shop is either the requester OR the supplier
    $query = itemRequestModel::query();
        $query->whereIn('account', $userAccounts);
    // ── Shop filter (if selected in dropdown) ─────────────────────────────
    if (!empty($shopFilter)) {
        $query->where(function ($q) use ($shopFilter) {
            $q->where('account', $shopFilter)
              ->orWhere('supplierId', $shopFilter);
        });
    }

    // ── Date range filter (FIXED) ────────────────────────────────────────
    if (!empty($dateFrom) && !empty($dateTo)) {
        // For a single day or date range
        $query->whereDate('created_at', '>=', $dateFrom)
              ->whereDate('created_at', '<=', $dateTo);
    } elseif (!empty($dateFrom)) {
        $query->whereDate('created_at', '>=', $dateFrom);
    } elseif (!empty($dateTo)) {
        $query->whereDate('created_at', '<=', $dateTo);
    }
    // If both are empty, no date filter is applied

    // Execute the query
    $requests = $query->orderBy('created_at', 'desc')->get();

    // Debug - check what dates were returned
    \Log::info('Query returned:', ['count' => $requests->count()]);
    if ($requests->count() > 0) {
        \Log::info('Date range in results:', [
            'min_date' => $requests->min('created_at'),
            'max_date' => $requests->max('created_at')
        ]);
    }

    // Attach user names for assigned_to
    $assignedToIds = $requests->pluck('assigned_to')->filter()->unique();
    $users = \App\Models\User::whereIn('id', $assignedToIds)->get()->keyBy('id');

    foreach ($requests as $request) {
        $product = null;
        
        if (!empty($request->productId)) {
            $product = productsModel::where('product_id', $request->productId)->first();
        }
        
        $productName = 'Unknown Product';
        if ($product) {
            $productName = $product->name ?? $product->name01 ?? $product->product_name ?? $product->title ?? 'Unknown Product';
        }
        
        $stockQty = 0;
        if ($product) {
            if (isset($product->quantity) && is_numeric($product->quantity)) {
                $stockQty = (int)$product->quantity;
            } elseif (isset($product->stock) && is_numeric($product->stock)) {
                $stockQty = (int)$product->stock;
            } elseif (isset($product->stock_quantity) && is_numeric($product->stock_quantity)) {
                $stockQty = (int)$product->stock_quantity;
            } else {
                $stockQty = ($product->stock === 'Available' || $product->quantity === 'Available') ? 999999 : 0;
            }
        }
        
        $request->productName = $productName;
        $request->stockQty = $stockQty;
        $request->inStock = $stockQty >= $request->quantity;
        $request->assignedToName = $users[$request->assigned_to]->name ?? null;
    }

    // Group by requestName
    $groupedRequests = [];
    foreach ($requests as $request) {
        $groupedRequests[$request->requestName][] = $request;
    }

    // Statistics based on GROUPED requests (unique request names)
    $totalRequest = count($groupedRequests);
    
    $pendingRequestIds = [];
    $submittedRequestIds = [];
    
    foreach ($requests as $req) {
        if ($req->status === 'Pending') {
            $pendingRequestIds[$req->requestName] = true;
        }
        if ($req->status === 'Submitted') {
            $submittedRequestIds[$req->requestName] = true;
        }
    }
    
    $totalPednding = count($pendingRequestIds);
    $totalSub = count($submittedRequestIds);

    // Convert inner arrays to collections
    foreach ($groupedRequests as $key => $itemArray) {
        $groupedRequests[$key] = collect($itemArray);
    }

    // ── Shops list for filter dropdown ───────────────────────────────────
    $shops = getUserAccounts();

    $data = compact(
        'groupedRequests',
        'totalRequest',
        'totalPednding',
        'totalSub',
        'requests',
        'account',
        'shops',
        'dateFrom',
        'dateTo',
        'shopFilter'
    );

        return view('viewRequest', $data);
 
}

public function viewRequestDetails(Request $req)
{
    $user = Auth::user();
    $account = getCurrentShopId();
    $requestId = $req->input('requestName');
    $account = $req->input('account', $account); 
    
    // Get all items for this request
    $items = itemRequestModel::where('requestName', $requestId)
        ->where('account', $account)
        ->orderBy('created_at', 'desc')
        ->get();
    
    if ($items->isEmpty()) {
        return redirect()->back()->with('error', 'Request not found');
    }
    
    // Get requester and supplier info
    $requesterAccount = $items[0]->account ?? '';
    $supplierAccount = $items[0]->supplierId ?? '';
    
    $requesterName = DB::table('accounts')->where('id', $requesterAccount)->value('name');
    $supplierName = DB::table('accounts')->where('id', $supplierAccount)->value('name');
    
    // Get user names for assigned_to
        $assignedToIds = $items->pluck('assigned_to')->filter()->unique();
        $users = \App\Models\User::whereIn('id', $assignedToIds)->get()->keyBy('id');

        // Preload products to avoid N+1 queries in the view
        $productIds = $items->pluck('productId')->filter()->unique();
        $products = DB::table('products')
            ->whereIn('product_id', $productIds)
            ->where('account', 7)
            ->get()
            ->keyBy('product_id');

        // Preload returned quantities per product for this request (view-only)

        // Use the earliest original receiving record for this request as the cutoff
        $earliestOriginalReceiving = DB::table('receivings')
            ->where('receivingName', $requestId)
            ->where('is_return', 0)
            ->whereIn('productId', $productIds)
            ->min('created_at');

        $returnCutoff = $earliestOriginalReceiving
            ?: ($items->min('created_at') ?? now());

        $returnedQuantities = DB::table('receivings')
            ->whereIn('productId', $productIds)
            ->where('account', $requesterAccount)
            ->where('is_return', 1)
            ->where('status', 'Returned')
            ->where('created_at', '>', $returnCutoff)
            ->get()
            ->groupBy('productId')
            ->map(function ($records) {
                return (int) $records->sum('quantity');
            });

        // Calculate totals and get product details
        $totalQuantity = 0;
        $totalReturnedQuantity = 0;
        $adjustedTotalQuantity = 0;
        $unhandledPrice = 0;
        $totalPrice = 0;
        $formattedItems = [];

        $returnsAppliedPerProduct = [];

        foreach ($items as $item) {
            $originalQty = (int) $item->quantity;
            $productId = $item->productId;
            $returnedQty = 0;
            $adjustedQty = $originalQty;

            if (!isset($returnsAppliedPerProduct[$productId])) {
                $returnsAppliedPerProduct[$productId] = true;
                $returnedQty = $returnedQuantities->get($productId, 0);
                $adjustedQty = $originalQty - $returnedQty;
            }

            $totalQuantity += $originalQty;
            $totalReturnedQuantity += $returnedQty;
            $adjustedTotalQuantity += $adjustedQty;
            if ($item->status === 'Approved') {
                $totalPrice += $adjustedQty * $item->price;
            } else {
                $unhandledPrice += $adjustedQty * $item->price;
            }

            $itemId = $item->id;

            $product = $products[$item->productId] ?? null;
            $productName = 'Unknown Product';
            $stockQty = 0;

            if ($product) {
                $productName = $product->name ?? $product->name01 ?? $product->product_name ?? 'Unknown Product';
                $stockQty = $product->stock ?? $product->quantity ?? 0;

                if (!is_numeric($stockQty)) {
                    $stockQty = ($stockQty == 'Available' || $stockQty == 'In Stock') ? 999999 : 0;
                } else {
                    $stockQty = (int)$stockQty;
                }
            }

            $formattedItems[] = (object)[
                'id' => $item->id,
                'productId' => $item->productId,
                'productName' => $productName,
                'quantity' => $originalQty,
                'adjustedQuantity' => $adjustedQty,
                'returnedQuantity' => $returnedQty,
                'price' => $item->price,
                'status' => $item->status,
                'payment_type' => $item->payment_type ?? 'cash',
                'stockQty' => $stockQty,
                'inStock' => $stockQty >= $adjustedQty,
                'assignedToName' => $users[$item->assigned_to]->name ?? null,
            ];
        }
    
    // Determine overall status
    $statuses = $items->pluck('status')->unique()->toArray();
    if (count($statuses) === 1) {
        $overallStatus = $statuses[0];
    } elseif (in_array('Pending', $statuses)) {
        $overallStatus = 'Pending';
    } elseif (in_array('Approved', $statuses)) {
        $overallStatus = 'Approved';
    } else {
        $overallStatus = 'Mixed';
    }
    
    $isAdmin = Auth::check() && Auth::user()->levelStatus === 'Admin';
    $iAmRequester = (getCurrentShopId() === (int)$requesterAccount);
    $iAmReceiver = (getCurrentShopId() === (int)$supplierAccount);

    $pillMap = [
        'Pending' => 'pill-pending',
        'Approved' => 'pill-approved',
        'Rejected' => 'pill-rejected',
        'Submitted' => 'pill-submitted',
        'Out of Stock' => 'pill-mixed',
        'Mixed' => 'pill-mixed',
    ];

    return view('viewRequestDetails', compact(
        'requestId',
        'itemId',
        'items',
        'formattedItems',
        'requesterName',
        'supplierName',
        'totalQuantity',
        'totalReturnedQuantity',
        'adjustedTotalQuantity',
        'totalPrice',
        'unhandledPrice',
        'overallStatus',
        'isAdmin',
        'iAmRequester',
        'iAmReceiver',
        'requesterAccount',
        'supplierAccount',
        'pillMap'
    ));
}
        public function redoRequest(Request $request) {
        $requestName = $request->input('requestName');

        $items = itemRequestModel::where('requestName', $requestName);
$items->update(['status' => 'Pending']);

        if ($items) {
            $log              = new logModal();
            $log->title       = 'Item Request Logs';
            $log->description = 'Request ' . $requestName . ' set back to Pending by ' . Auth::user()->name;
            $log->save();
            return redirect()->back()->with('success', 'Request set back to Pending');
        }
        return redirect()->back()->with('error', 'Failed to set back to Pending');

        }
    // ── Approve All ─────────────────────────────────────────────────────────
    public function approveAll(Request $request) {
        $requestName = $request->input('requestName');
        $supplierId  = $request->input('supplierId');
        $status  = $request->input('status');

        $items = itemRequestModel::where('requestName', $requestName)
            ->where('supplierId', $supplierId)
            ->where('status', 'Submitted')
            ->get();

        $approvedCount = 0;
        $rejectedCount = 0;
        $skippedCount = 0;
        $skippedItems = [];

        foreach ($items as $item) {

        if($status === 'reject') {
            $item->status = 'Rejected';
            $item->save();

            $rejectedCount++;
            }
    if($status === 'approve') {
        
    if ($item->status === 'Approved') {
            $duplicateCount++;
            continue;
        }
        
            // ✅ Check stock availability FIRST
            $get = productsModel::where('product_id', $item->productId)
                ->where('account', $supplierId)
                ->first();

            if (!$get || $get->quantity < $item->quantity) {
                $skippedCount++;
                $skippedItems[] = $get->name01 ?? 'Unknown Product';
                continue; // skip out of stock items
            }
            
            
            $item->status = 'Approved';
            $item->save();

            $get->quantity -= $item->quantity;
            $get->save();

            $productPrice = $get->sPrice ?? 0;
            $wholesalePrice = $get->wPrice ?? 0;

            $log              = new logModal();
            $log->title       = 'Stock Log';
            $log->description = $get->name01 . ' Stock Deducted ' . $item->quantity
                . ' by ' . Auth::user()->name . ' — inter-shop transfer';
            $log->save();

            // Sales record
            $sale               = new salsModel();
            $sale->sales_id     = $item->requestName . '-' . $item->productId;
            $sale->salesName    = $item->requestName;
            $sale->stockId      = $item->productId;
            $sale->cName        = 'Inter-Shop Sale';
            $sale->cPhone       = '';
            $sale->productId    = $item->productId;
            $sale->pQuantity    = $item->quantity;
            $sale->productPrice = $productPrice;
            $sale->totalPrice   = $item->price;
            $sale->created_at = $item->created_at;
            $sale->served_by    = Auth::user()->name;
            $sale->account      = getCurrentShopId();
            $sale->save();

            // Receiving record (for Shop 1 to accept)
            $receivingId = date('Ymd') . '-' . str_pad(
                recevingModel::whereDate('created_at', date('Y-m-d'))->count() + 1,
                6, '0', STR_PAD_LEFT
            );
            
            if($item->payment_type == 'credit') {
                $credit = 1;
                $cash = 0;
            } else {
                $credit = 0;
                $cash = 1;
            }


            $receiving           = new recevingModel();
            $receiving->receivingName = $item->requestName;
            $receiving->receivingId = $receivingId;
            $receiving->productId = $item->productId;
            $receiving->quantity  = $item->quantity;
            $receiving->price     = $item->price;
            $receiving->sellingPrice = $productPrice;
            $receiving->wholesalePrice = $wholesalePrice;
            $receiving->isDebt = $credit;
            $receiving->isPaid = $cash;
            $receiving->is_return = 0;
            $receiving->account   = $item->account;
            $receiving->served_by = $item->assigned_to ?? Auth::user()->name;
            $receiving->supplier  = $item->supplierId;
            $receiving->created_at = $item->created_at;
            $receiving->status    = 'Not Approved';
            $receiving->save();
            
            $approvedCount++;
            }
            

        }

        $log              = new logModal();
        $log->title       = 'Item Request Logs';
        if ($skippedCount > 0) {
            $log->description = 'Approved ' . $approvedCount . ' items, skipped ' . $skippedCount . ' out-of-stock items: ' . implode(', ', $skippedItems) . ' in request ' . $requestName . ' approved by ' . Auth::user()->name;
        } else if ($rejectedCount > 0) {
            $log->description = 'All items in request ' . $requestName . ' Rejected by ' . Auth::user()->name;
        } else {
            $log->description = 'All items in request ' . $requestName . ' approved by ' . Auth::user()->name;
        }
        $log->save();

        if ($skippedCount > 0) {
            return redirect()->back()->with('error', 'Approved ' . $approvedCount . ' items. Skipped ' . $skippedCount . ' out-of-stock items: ' . implode(', ', $skippedItems));
        } else {
            return redirect()->back()->with('success', 'All items approved successfully');
        }
    }

    // ── Create item request ──────────────────────────────────────────────────
    public function itemRequest(Request $request) {
        $pId              = $request->input('pId');
        $quantity         = $request->input('pQuantity');
        $price            = $request->input('pPrice');
        $paymentType      = $request->input('paymentType', 'credit'); // Default to 'credit'
        $assignedTo       = $request->input('assignedTo'); // User to assign the request to (can be null)
        $requestDate      = $request->input('requestDate');

        if (empty($pId)) {
            return redirect()->back()->with('error', 'Please select a product');
        }

        $productsPrice = productsModel::where('product_id', $pId)->where('account', '7')->value('sPrice');

    
        
        $activeRequest = itemRequestModel::where('account', getCurrentShopId())
            ->where('status', 'Pending')
            ->orderBy('id', 'desc')
            ->first();

        if ($activeRequest) {
            $requestName = $activeRequest->requestName;
            $createdAt   = !empty($requestDate)
                ? $requestDate . ' ' . date('H:i:s')
                : date('Y-m-d H:i:s');

            if (!empty($requestDate)) {
                $activeRequest->created_at = $createdAt;
                $activeRequest->save();
            }
            $activeRequestProd = itemRequestModel::where('productId', $pId)
        ->where('account', getCurrentShopId())
            ->where('status', 'Pending')
            ->orderBy('id', 'desc')
            ->first();

            if($activeRequestProd) {
                $activeRequestProd->update(([
                    'quantity' => ($activeRequestProd->quantity + $quantity)
                ]));

                return redirect()->back()->with('success', 'Item Request Updated');
            }
        } else {
            $requestName = date('Ymd') . '-' . str_pad(
                itemRequestModel::whereDate('created_at', date('Y-m-d'))->count() + 1,
                4, '0', STR_PAD_LEFT
            );
            $createdAt   = !empty($requestDate)
                ? $requestDate . ' ' . date('H:i:s')
                : date('Y-m-d H:i:s');
        }

        $username = Auth::user()->name;
        if (empty($username)) {
            return redirect()->back()->with('success', 'Session username is empty');
        }

        $totalPrice = $quantity * $price;

        $insert = itemRequestModel::create([
            'requestName'   => $requestName,
            'productId'     => $pId,
            'quantity'      => $quantity,
            'price'         => $price,
            'totalPrice'    => $totalPrice,
            'payment_type'  => $paymentType,
            'assigned_to'   => $assignedTo,
            'assigned_by'   => $username,
            'supplierId'    => 7,
            'account'       => getCurrentShopId(),
            'served_by'     => $username,
            'status'        => 'Pending',
            'created_at'    => $createdAt,
        ]);


        if ($insert) {
            $log              = new logModal();
            $log->title       = 'Item Request Logs';
            $log->description = $insert->requestName . ' Item Request Created By ' . Auth::user()->name . ' (' . $paymentType . ') - Assigned to: ' . ($assignedTo ?? 'Unassigned');
            $log->save();
            return redirect()->back()->with('success', 'Item Request Created');
        }

        return redirect()->back()->with('error', 'Failed to create item request');
    }

    // ── Update quantity ──────────────────────────────────────────────────────
    public function updQuant(Request $request) {
        $requestName  = $request->input('OrdersIds');
        $prodId       = $request->input('prodId');
        $prodQuantity = $request->input('prodQuantity');

        $item = itemRequestModel::where('account', getCurrentShopId())
            ->where('requestName', $requestName)
            ->where('productId', $prodId)
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found');
        }

        $item->quantity   = $prodQuantity;
        $item->totalPrice = $prodQuantity * $item->price;
        $item->save();

        $log              = new logModal();
        $log->title       = 'Item Request Logs';
        $log->description = 'Quantity updated for item ' . $prodId . ' in request ' . $requestName . ' by ' . Auth::user()->name;
        $log->save();

        return redirect()->back()->with('success', 'Quantity updated successfully');
    }

    // ── Delete item from order ───────────────────────────────────────────────
    public function dltProdOrd(Request $request) {
        $requestName = $request->input('OrdersIds');
        $prodId      = $request->input('prodId');

        $item = itemRequestModel::where('account', getCurrentShopId())
            ->where('requestName', $requestName)
            ->where('productId', $prodId)
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found');
        }

        $item->delete();

        $log              = new logModal();
        $log->title       = 'Item Request Logs';
        $log->description = 'Item ' . $prodId . ' deleted from request ' . $requestName . ' by ' . Auth::user()->name;
        $log->save();

        return redirect()->back()->with('success', 'Item removed successfully');
    }

    // ── Save supplier / shop / assignment info ────────────────────────────────
    public function saveInfo(Request $request) {
        $requestName = $request->input('requestName');
        $SupllierId  = $request->input('selectedCustomer');
        $assignedTo  = $request->input('assignedTo');
        $paymentType = $request->input('paymentType');

        \Log::info('saveInfo inputs:', $request->all());
        \Log::info('Session account: ' . getCurrentShopId());

        $saved = false;

        // Save assigned_to if providedf
        if (!empty($assignedTo)) {
            itemRequestModel::where('account', getCurrentShopId())
                ->where('requestName', $requestName)
                ->update(['assigned_to' => $assignedTo]);
            $saved = true;
        }

        // Save payment_type if provided
        if (!empty($paymentType)) {
            itemRequestModel::where('account', getCurrentShopId())
                ->where('requestName', $requestName)
                ->update(['payment_type' => $paymentType]);
            $saved = true;
        }

        // Save supplierId if provided
        if (!empty($SupllierId)) {
            itemRequestModel::where('account', getCurrentShopId())
                ->where('requestName', $requestName)
                ->update(['supplierId' => $SupllierId]);
            $saved = true;
        }

        if ($saved) {
            $log              = new logModal();
            $log->title       = 'Item Request Logs';
            $log->description = 'Info saved for request ' . $requestName . ' by ' . Auth::user()->name
                . ' - Supplier: ' . ($SupllierId ?? 'N/A')
                . ', Assigned: ' . ($assignedTo ?? 'N/A')
                . ', Payment: ' . ($paymentType ?? 'N/A');
            $log->save();
            return redirect()->back()->with('success', 'Request info saved');
        }

        return redirect()->back()->with('error', 'Failed to save info. No items found for this request.');
    }

    // ── Submit (payout) ──────────────────────────────────────────────────────
    public function payout(Request $request) {
        $requestName      = $request->input('requestName');
        $requestDatePicker = $request->input('requestDatePicker');
        
        if (empty($requestDatePicker)) {
            $requestDatePicker = date('Y-m-d H:i:s');
        }
        // Get all items in this request to update their prices to current values
        $items = itemRequestModel::where('account', getCurrentShopId())
            ->where('requestName', $requestName)
            ->get();

        // Update each item with current product price
        foreach ($items as $item) {
            $product = productsModel::where('product_id', $item->productId)
                ->where('account', 7)
                ->first();
            
            if ($product) {
                $currentPrice = $product->sPrice ?? 0;
                $item->price = $currentPrice;
                $item->totalPrice = $item->quantity * $currentPrice;
                $item->save();
            }
        }

        $updated = itemRequestModel::where('account', getCurrentShopId())
            ->where('requestName', $requestName)
            ->update(['status' => 'Submitted',
             'created_at' => $requestDatePicker,
             'account' => getCurrentShopId()]);

        if ($updated > 0) {
            $log              = new logModal();
            $log->title       = 'Item Request Logs';
            $log->description = 'Item request ' . $requestName . ' submitted by ' . Auth::user()->name;
            $log->save();
            return redirect()->back()->with('success', 'Item request submitted successfully');
        }

        return redirect()->back()->with('error', 'Failed to submit item request');
    }

    // ── Approve single item ──────────────────────────────────────────────────
    public function approveRequest(Request $request) {
        $productId   = $request->input('product_id');
        $itemId = $request->input('requestName');
        $supplierId  = (int) $request->input('supplierId');

        // ✅ FIX: GET ALL matching items not just FIRST one
        $item = itemRequestModel::where('id', $itemId)
            ->where('status', 'Submitted')
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found or already processed');
        }

            // ✅ Check stock availability FIRST before approving
            $get = productsModel::where('product_id', $productId)
                ->where('account', 7)
                ->first();

            if (!$get || $get->quantity < $item->quantity) {
                return redirect()->back()->with('error', 'Cannot approve. Product is out of stock.');
            }

            $item->status = 'Approved';
            $item->save();

            // Deduct stock
            $get->quantity -= $item->quantity;
            $get->save();

            $productPrice = $get->sPrice ?? 0;
            $wholesalePrice = $get->wPrice ?? 0;

            $log              = new logModal();
            $log->title       = 'Stock Log';
            $log->description = $get->name01 . ' Stock Deducted ' . $item->quantity
                . ' by ' . Auth::user()->name . ' — inter-shop transfer';
            $log->save();

            // Sales record
            $sale               = new salsModel();
            $sale->sales_id     = $item->requestName . '-' . $item->id . '-' . $productId;
            $sale->salesName    = $item->requestName;
            $sale->stockId      = $item->productId;
            $sale->cName        = 'Inter-Shop Sale';
            $sale->cPhone       = '';
            $sale->productId    = $item->productId;
            $sale->pQuantity    = $item->quantity;
            $sale->productPrice = $item->price;
            $sale->totalPrice   = $item->quantity * $item->price;
            $sale->served_by    = Auth::user()->name;
            $sale->account      = getCurrentShopId();
            $sale->save();

            // Generate proper receiving ID
            $receivingId = date('Ymd') . '-' . str_pad(
                recevingModel::whereDate('created_at', date('Y-m-d'))->count() + 1,
                6, '0', STR_PAD_LEFT
            );

             if($item->payment_type == 'credit') {
                $credit = 1;
                $cash = 0;
            } else {
                $credit = 0;
                $cash = 1;
            }
            
            $receiving  = new recevingModel();
            $receiving->receivingName = $item->requestName;
            $receiving->receivingId = $receivingId;
            $receiving->productId = $item->productId;
            $receiving->quantity  = $item->quantity;
            $receiving->price     = $item->price;
            $receiving->sellingPrice = $productPrice;
            $receiving->wholesalePrice = $wholesalePrice;
            $receiving->account   = $item->account;
            $receiving->isDebt = $credit;
            $receiving->isPaid = $cash;
            $receiving->is_return = 0;
            $receiving->account   = $item->account;
            $receiving->served_by = $item->assigned_to ?? Auth::user()->name;
            $receiving->supplier  = $item->supplierId;
            $receiving->status    = 'Not Approved';
            $receiving->save();


        $log              = new logModal();
        $log->title       = 'Item Request Logs';
        $log->description = 'Item ' . $productId . ' approved by ' . Auth::user()->name;
        $log->save();

        return redirect()->back()->with('success', 'Item approved successfully');
    }

    // ── Reject single item ───────────────────────────────────────────────────
    public function rejectRequest(Request $request) {
        $productId  = $request->input('product_id');
        $supplierId = $request->input('supplierId', getCurrentShopId());
        $requestName = $request->input('requestName');

        $item = itemRequestModel::where('requestName', $requestName)
            ->where('supplierId', $supplierId)
            ->where('productId', $productId)
            ->where('status', 'Submitted')
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found or already processed');
        }

        $item->status = 'Rejected';
        $item->save();

        $log              = new logModal();
        $log->title       = 'Item Request Logs';
        $log->description = 'Item ' . $productId . ' rejected by ' . Auth::user()->name;
        $log->save();

        return redirect()->back()->with('success', 'Item rejected');
    }

    // ── Out of stock ─────────────────────────────────────────────────────────
    public function outOfStockRequest(Request $request) {
        $productId  = $request->input('product_id');
        $supplierId = $request->input('supplierId', getCurrentShopId());

        $item = itemRequestModel::where('supplierId', $supplierId)
            ->where('productId', $productId)
            ->where('status', 'Submitted')
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found or already processed');
        }

        $item->status = 'Out of Stock';
        $item->save();

        $log              = new logModal();
        $log->title       = 'Item Request Logs';
        $log->description = 'Item ' . $productId . ' marked as out of stock by ' . Auth::user()->name;
        $log->save();

        return redirect()->back()->with('success', 'Item marked as out of stock');
    }

    // ── Delete item request ──────────────────────────────────────────────────
    public function dltItemReq(Request $req) {
        $itemId  = $req->input('itemId') ?? $req->input('product_id');
        $reqName = $req->input('reqName') ?? $req->input('requestName');

        $dlt = itemRequestModel::where('requestName', $reqName)
            ->where('productId', $itemId)
            ->delete();

        if ($dlt) {
            return redirect()->back()->with('success', 'Item removed');
        }
        return redirect()->back()->with('error', 'Item not removed');
    }

    // ── Bulk item request from products page ────────────────────────────────
    public function bulkRequest(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'required|exists:products,product_id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'supplier_id' => 'nullable|integer|exists:accounts,id',
        ]);

        $productIds = $request->input('product_ids', []);
        $quantities = $request->input('quantities', []);
        $supplierId = $request->input('supplier_id');

        if (count($productIds) !== count($quantities)) {
            return redirect()->back()->with('error', 'Invalid request payload');
        }

        $username = Auth::user()->name ?? 'Unknown';

        $requestName = date('Ymd') . '-' . str_pad(
            itemRequestModel::whereDate('created_at', date('Y-m-d'))->count() + 1,
            4, '0', STR_PAD_LEFT
        );

        foreach ($productIds as $index => $productId) {
            $qty = (int) ($quantities[$index] ?? 1);
            if ($qty <= 0) continue;

            $price = productsModel::where('product_id', $productId)
                ->where('account', '7')
                ->value('sPrice');

            $totalPrice = $qty * ($price ?? 0);

            itemRequestModel::create([
                'requestName' => $requestName,
                'productId'   => $productId,
                'quantity'    => $qty,
                'price'       => $price ?? 0,
                'totalPrice'  => $totalPrice,
                'account'     => getCurrentShopId(),
                'supplierId'  => $supplierId,
                'status'      => 'Pending',
                'served_by'   => $username,
                'assigned_by' => $username,
            ]);
        }

        $log = new logModal();
        $log->title = 'Item Request Logs';
        $log->description = 'Bulk item request ' . $requestName . ' created by ' . $username
            . ' (' . count($productIds) . ' items)';
        $log->save();

        return redirect()->back()->with('success', 'Item request submitted successfully');
    }

    // ── Delete entire request (Admin only) ───────────────────────────────────
    public function deleteRequest(Request $request) {
        $requestName = $request->input('requestName');

        // Delete all items with this requestName
        $deleted = itemRequestModel::where('requestName', $requestName)->delete();

        if ($deleted) {
            $log = new logModal();
            $log->title = 'Item Request Logs';
            $log->description = 'Request ' . $requestName . ' deleted entirely by ' . Auth::user()->name;
            $log->save();

            return redirect()->back()->with('success', 'Request deleted successfully');
        }

        return redirect()->back()->with('error', 'Request not found or already deleted');
    }
}