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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use function getCurrentShopId;

class itemRequestController extends Controller
{
    public function index() {
        $user = Auth::user();
        $isAdmin = strtolower(trim($user->levelStatus)) === 'admin';

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

        // ── Shops for the shop selector ───────────────────────────────────────
        // Admin: all shops; User: only shops assigned to them
        if ($isAdmin) {
            $shops = DB::table('accounts')->orderBy('name', 'asc')->get();
        } else {
            $myShopIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($myShopIds)) {
                $shops = DB::table('accounts')->where('id', '=', 0)->get();
            } else {
                $shops = DB::table('accounts')->whereIn('id', $myShopIds)->orderBy('name', 'asc')->get();
            }
        }

        // ── Suppliers (Customers / Accounts) ──────────────────────────────────
        // Admin: see all accounts except the current session account
        // User:  see only the accounts they are assigned to via user_accounts
        if ($isAdmin) {
            $Customers = DB::table('accounts')
                ->where('id', '!=', getCurrentShopId())
                ->get();
        } else {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($assignedAccountIds)) {
                $Customers = DB::table('accounts')->where('id', '=', 0)->get(); // empty
            } else {
                $Customers = DB::table('accounts')->whereIn('id', $assignedAccountIds)->get();
            }
        }

        // ── Users for "Assign to" dropdown ────────────────────────────────────
        // Admin: see all non-Admin users
        // User:  see only users who share at least one assigned account with the
        //        currently logged-in user (i.e. users they have access to)
        if ($isAdmin) {
            $users = DB::table('users')
                ->where('levelStatus', '!=', 'Admin')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            // Get ALL account IDs the current user is assigned to
            $myAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($myAccountIds)) {
                $users = DB::table('users')->where('id', '=', 0)->get();
            } else {
                // Find all user IDs assigned to any of the current user's accounts (including self)
                $accessibleUserIds = DB::table('user_accounts')
                    ->whereIn('account', $myAccountIds)
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
            }
        }

        $data = compact('products', 'orders', 'Customers','supplierName','Allocation','carts', 'users', 'shops');

        if ($isAdmin) {
            return view('admin.item_request', $data);
        }
        if (!empty($user->levelStatus)) {
            return view('user.item_request', $data);
        }
    }

    /**
     * Main Store Report
     * Tracks all items requested from Main Store (account 7) by other shops.
     */
    public function mainStoreReport(Request $req)
    {
        $user = Auth::user();
        if (strtolower(trim($user->levelStatus)) !== 'admin' && !canUser('view_full_report')) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $dateFrom = $req->input('date_from', date('Y-m-d'));
        $dateTo = $req->input('date_to', date('Y-m-d'));
        $mainStoreId = 7;

        // Fetch requests where Main Store is the supplier
        $query = itemRequestModel::where('supplierId', $mainStoreId);

        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        }

        $allRequests = $query->orderBy('created_at', 'desc')->get();

        // Get requester names
        $requesterIds = $allRequests->pluck('account')->unique();
        $requestersMap = DB::table('accounts')->whereIn('id', $requesterIds)->pluck('name', 'id');

        // Group by Date and Requester Shop
        $reportRows = $allRequests->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->toDateString() . '_' . $item->account;
        })->map(function ($group) use ($requestersMap) {
            $first = $group->first();
            $shopId = $first->account;
            $date = Carbon::parse($first->created_at)->toDateString();
            
            return (object)[
                'date' => $date,
                'shop_id' => $shopId,
                'shop_name' => $requestersMap[$shopId] ?? 'Unknown Shop',
                'total_qty' => $group->sum('quantity'),
                'total_value' => $group->sum('totalPrice'),
                'approved_value' => $group->where('status', 'Approved')->sum('totalPrice'),
                'credit_value' => $group->where('payment_type', 'credit')->sum('totalPrice'),
                'cash_value' => $group->where('payment_type', 'cash')->sum('totalPrice'),
                'pending_value' => $group->where('status', 'Submitted')->sum('totalPrice'),
                'items' => $group
            ];
        })->sortByDesc('date');
    
    /**
     * Mark inter-shop credit requests as paid.
     */
   

        $grandTotals = (object)[
            'value' => $allRequests->sum('totalPrice'),
            'approved' => $allRequests->where('status', 'Approved')->sum('totalPrice'),
            'credit' => $allRequests->where('payment_type', 'credit')->sum('totalPrice'),
        ];

        return view('admin.mainStoreReport', compact('reportRows', 'grandTotals', 'dateFrom', 'dateTo'));
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
    $user    = Auth::user();

    // ── Filter inputs ─────────────────────────────────────────────────────
    $dateFrom  = $request->query('date_from', date('Y-m-d'));
    $dateTo    = $request->query('date_to', date('Y-m-d'));
    $shopFilter = $request->query('shop'); 
    
    session([
        'selected_shop_id' => $shopFilter,
    ]);
    $account = getCurrentShopId();

    // Base query: requests where this shop is either the requester OR the supplier
    $query = itemRequestModel::where(function ($q) use ($account) {
            $q->where('account', $account);
        })
        ->orWhere(function ($q) use ($account) {
            $q->where('supplierId', $account);
        });

    // ── Date range filter (server-side) ───────────────────────────────────
    if ($dateFrom) {
        $query->whereDate('created_at', '>=', $dateFrom);
    }
    if ($dateTo) {
        $query->whereDate('created_at', '<=', $dateTo);
    }

    // ── Shop filter ───────────────────────────────────────────────────────
    if ($shopFilter) {
        $query->where(function ($q) use ($shopFilter) {
            $q->where('account', $shopFilter)
              ->orWhere('supplierId', $shopFilter);
        });
    }

    $requests = $query->orderBy('created_at', 'desc')->get();

    // Attach user names for assigned_to
    $assignedToIds = $requests->pluck('assigned_to')->filter()->unique();
    $users         = \App\Models\User::whereIn('id', $assignedToIds)->get()->keyBy('id');

    foreach ($requests as $request) {
        // FIXED: Look up the product by ID properly
        $product = null;
        
        if (!empty($request->productId)) {
            // Try multiple column names that might contain the product ID
            $product = productsModel::where('product_id', $request->productId)
                        ->first();
            
            // Debug: Log what we found
            \Log::info('Product lookup:', [
                'searching_for' => $request->productId,
                'found' => $product ? 'yes' : 'no',
                'product_data' => $product ? $product->toArray() : null
            ]);
        }
        
        // Get product name - check all possible name fields
        $productName = 'Unknown Product';
        if ($product) {
            $productName = $product->name ?? $product->name01 ?? $product->product_name ?? $product->title ?? 'Unknown Product';
        }
        
        // Get stock quantity - check all possible stock fields
        $stockQty = 0;
        if ($product) {
            // Try numeric stock fields first
            if (isset($product->quantity) && is_numeric($product->quantity)) {
                $stockQty = (int)$product->quantity;
            } elseif (isset($product->stock) && is_numeric($product->stock)) {
                $stockQty = (int)$product->stock;
            } elseif (isset($product->stock_quantity) && is_numeric($product->stock_quantity)) {
                $stockQty = (int)$product->stock_quantity;
            } else {
                // If stock is a string like "Available", set to a large number or 0
                $stockQty = ($product->stock === 'Available' || $product->quantity === 'Available') ? 999999 : 0;
            }
        }
        
        $request->productName = $productName;
        $request->stockQty    = $stockQty;
        $request->inStock     = $stockQty >= $request->quantity;
        $request->assignedToName = $users[$request->assigned_to]->name ?? null;
    }

    // Group by requestName
    $groupedRequests = [];
    foreach ($requests as $request) {
        $groupedRequests[$request->requestName][] = $request;
    }

    // ── Shops list for filter dropdown ───────────────────────────────────
    $shops = DB::table('accounts')->orderBy('name', 'asc')->get();

    // ── Statistics ──────────────────────────────────────────────────────
    $totalRequest = count($groupedRequests);

    $pendingRequestIds   = [];
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
    $totalSub      = count($submittedRequestIds);

    // Convert inner arrays to collections
    foreach ($groupedRequests as $key => $itemArray) {
        $groupedRequests[$key] = collect($itemArray);
    }

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

    if (strtolower(trim($user->levelStatus)) === 'admin') {
        return view('admin.viewRequest', $data);
    }
    if (!empty($user->levelStatus)) {
        return view('user.viewRequest', $data);
    }
}

public function viewRequestDetails($requestId)
{
    $user = Auth::user();
    $account = getCurrentShopId();
    
    // Get all items for this request
    $items = itemRequestModel::where('requestName', $requestId)
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
    
    // Calculate totals and get product details
    $totalQuantity = 0;
    $totalPrice = 0;
    $formattedItems = [];
    
    foreach ($items as $item) {
        $totalQuantity += $item->quantity;
        $totalPrice += $item->quantity * $item->price;
        
        // Look up product details
        $product = null;
        $productName = 'Unknown Product';
        $stockQty = 0;
        
        if (!empty($item->productId)) {
            // Try multiple column names
            $product = DB::table('products')
                ->Where('product_id', $item->productId)
                ->first();
            
            if ($product) {
                $productName = $product->name ?? $product->name01 ?? $product->product_name ?? 'Unknown Product';
                $stockQty = $product->stock ?? $product->quantity ?? 0;
                
                // If stock is text like "Available", treat as large number
                if (!is_numeric($stockQty)) {
                    $stockQty = ($stockQty == 'Available' || $stockQty == 'In Stock') ? 999999 : 0;
                } else {
                    $stockQty = (int)$stockQty;
                }
            }
        }
        
        $formattedItems[] = (object)[
            'productId' => $item->productId,
            'productName' => $productName,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'status' => $item->status,
            'payment_type' => $item->payment_type ?? 'cash',
            'stockQty' => $stockQty,
            'inStock' => $stockQty >= $item->quantity,
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
    
    return view('admin.viewRequestDetails', compact(
        'requestId',
        'items',
        'formattedItems',
        'requesterName',
        'supplierName',
        'totalQuantity',
        'totalPrice',
        'overallStatus',
        'isAdmin',
        'iAmRequester',
        'iAmReceiver',
        'requesterAccount',
        'supplierAccount'
    ));
}
        public function redoRequest(Request $request) {
        $requestName = $request->input('requestName');

        $items = itemRequestModel::where('requestName', $requestName)
            ->where('status', 'Submitted')
            ->update(
                ['status' => 'Pending']
            );
        if ($items) {
            $log              = new logModal();
            $log->title       = 'Item Request Logs';
            $log->description = 'Request ' . $requestName . ' set back to Pending by ' . session('username');
            $log->save();
            return redirect()->back()->with('success', 'Request set back to Pending');
        }
        return redirect()->back()->with('error', 'Failed to set back to Pending');

        }
    // ── Approve All ─────────────────────────────────────────────────────────
    public function approveAll(Request $request) {
        $requestName = $request->input('requestName');
        $supplierId  = $request->input('supplierId');

        $items = itemRequestModel::where('requestName', $requestName)
            ->where('supplierId', $supplierId)
            ->where('status', 'Submitted')
            ->get();

        $approvedCount = 0;
        $skippedCount = 0;
        $skippedItems = [];

        foreach ($items as $item) {
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
                . ' by ' . session('username') . ' — inter-shop transfer';
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
            $receiving->status    = 'Not Approved';
            $receiving->save();
            
            $approvedCount++;
        }

        $log              = new logModal();
        $log->title       = 'Item Request Logs';
        if ($skippedCount > 0) {
            $log->description = 'Approved ' . $approvedCount . ' items, skipped ' . $skippedCount . ' out-of-stock items: ' . implode(', ', $skippedItems) . ' in request ' . $requestName . ' approved by ' . session('username');
        } else {
            $log->description = 'All items in request ' . $requestName . ' approved by ' . session('username');
        }
        $log->save();

        if ($skippedCount > 0) {
            return redirect()->back()->with('warning', 'Approved ' . $approvedCount . ' items. Skipped ' . $skippedCount . ' out-of-stock items: ' . implode(', ', $skippedItems));
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
                : $activeRequest->created_at;

            if (!empty($requestDate)) {
                $activeRequest->created_at = $createdAt;
                $activeRequest->save();
            }
        } else {
            $requestName = date('Ymd') . '-' . str_pad(
                itemRequestModel::whereDate('created_at', date('Y-m-d'))->count() + 1,
                4, '0', STR_PAD_LEFT
            );
            $createdAt = $requestDate ? $requestDate . ' ' . date('H:i:s') : now();
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
            'account'       => getCurrentShopId(),
            'shop_id'       => getCurrentShopId(),
            'served_by'     => $username,
            'status'        => 'Pending',
            'created_at'    => $createdAt,
        ]);


        if ($insert) {
            $log              = new logModal();
            $log->title       = 'Item Request Logs';
            $log->description = $insert->requestName . ' Item Request Created By ' . session('username') . ' (' . $paymentType . ') - Assigned to: ' . ($assignedTo ?? 'Unassigned');
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
        $log->description = 'Quantity updated for item ' . $prodId . ' in request ' . $requestName . ' by ' . session('username');
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
        $log->description = 'Item ' . $prodId . ' deleted from request ' . $requestName . ' by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Item removed successfully');
    }

    // ── Save supplier / shop / assignment info ────────────────────────────────
    public function saveInfo(Request $request) {
        $requestName = $request->input('requestName');
        $SupllierId  = $request->input('selectedCustomer');
        $assignedTo  = $request->input('assignedTo');

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
            $log->description = 'Info saved for request ' . $requestName . ' by ' . session('username')
                . ' - Supplier: ' . ($SupllierId ?? 'N/A')
                . ', Assigned: ' . ($assignedTo ?? 'N/A')
                . ', Shop: ' . ($shopId ?? 'N/A');
            $log->save();
            return redirect()->back()->with('success', 'Request info saved');
        }

        return redirect()->back()->with('error', 'Failed to save info. No items found for this request.');
    }

    // ── Submit (payout) ──────────────────────────────────────────────────────
    public function payout(Request $request) {
        $requestName      = $request->input('requestName');
        $requestDatePicker = $request->input('requestDatePicker');

        // Get all items in this request to update their prices to current values
        $items = itemRequestModel::where('account', getCurrentShopId())
            ->where('requestName', $requestName)
            ->get();

        // Update each item with current product price
        foreach ($items as $item) {
            $product = productsModel::where('product_id', $item->productId)
                ->where('account', getCurrentShopId())
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
            $log->description = 'Item request ' . $requestName . ' submitted by ' . session('username');
            $log->save();
            return redirect()->back()->with('success', 'Item request submitted successfully');
        }

        return redirect()->back()->with('error', 'Failed to submit item request');
    }

    // ── Approve single item ──────────────────────────────────────────────────
    public function approveRequest(Request $request) {
        $productId   = $request->input('product_id');
        $requestName = $request->input('requestName');
        $supplierId  = (int) $request->input('supplierId');

        // ✅ FIX: GET ALL matching items not just FIRST one
        $item = itemRequestModel::where('supplierId', $supplierId)
            ->where('productId', $productId)
            ->where('requestName', $requestName)
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
                . ' by ' . session('username') . ' — inter-shop transfer';
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
        $log->description = 'Item ' . $productId . ' approved by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Item approved successfully');
    }

    // ── Reject single item ───────────────────────────────────────────────────
    public function rejectRequest(Request $request) {
        $productId  = $request->input('product_id');
        $supplierId = $request->input('supplierId', getCurrentShopId());

        $item = itemRequestModel::where('supplierId', $supplierId)
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
        $log->description = 'Item ' . $productId . ' rejected by ' . session('username');
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
        $log->description = 'Item ' . $productId . ' marked as out of stock by ' . session('username');
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

    // ── Delete entire request (Admin only) ───────────────────────────────────
    public function deleteRequest(Request $request) {
        $requestName = $request->input('requestName');

        // Delete all items with this requestName
        $deleted = itemRequestModel::where('requestName', $requestName)->delete();

        if ($deleted) {
            $log = new logModal();
            $log->title = 'Item Request Logs';
            $log->description = 'Request ' . $requestName . ' deleted entirely by ' . session('username');
            $log->save();

            return redirect()->back()->with('success', 'Request deleted successfully');
        }

        return redirect()->back()->with('error', 'Request not found or already deleted');
    }
}