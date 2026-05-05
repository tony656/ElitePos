<?php

namespace App\Http\Controllers;

use App\Models\itemRequestModel;
use Illuminate\Http\Request;
use App\Models\logModal;
use App\Models\productsModel;
use Illuminate\Support\Facades\DB;
use App\Models\recevingModel;
use App\Models\salsModel;
use Illuminate\Support\Facades\Auth;
use function getSessionAccountName;

class itemRequestController extends Controller
{
    public function index() {
        $user = Auth::user();

        $products = productsModel::where('account', getSessionAccountName())->get();

        $orders = itemRequestModel::where('account', getSessionAccountName())
            ->where('served_by', session('username'))
            ->where('status', '!=', 'Submitted')
            ->orderBy('id', 'desc')
            ->first();

        $data = compact('products', 'orders');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.item_request', $data);
        }
        if (!empty($user->levelStatus)) {
            return view('user.item_request', $data);
        }
    }

    public function viewRequest() {
        $user    = Auth::user();
        $account = getSessionAccountName();

        // Get all requests where this shop is either the requester OR the supplier
        $requests = itemRequestModel::where(function ($query) use ($account) {
                $query->where('account', $account);
            })
            ->orWhere(function ($query) use ($account) {
                $query->where('supplierName', $account)
                      ->whereNotNull('supplierName');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Attach product names AND STOCK QUANTITY
        $productIds = $requests->pluck('productId')->unique();
        $products   = productsModel::whereIn('product_id', $productIds)
                        ->where('account', getSessionAccountName())
                        ->get()
                        ->keyBy('product_id');

        foreach ($requests as $request) {
            $request->productName = $products[$request->productId]->name01 ?? 'Unknown Product';
            $request->stockQty = $products[$request->productId]->quantity ?? 0;
            $request->inStock = isset($products[$request->productId]) && $products[$request->productId]->quantity >= $request->quantity;
        }

        // Group by requestName
        $groupedRequests = [];
        foreach ($requests as $request) {
            $groupedRequests[$request->requestName][] = $request;
        }

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

        // Convert inner arrays to collections so ->toArray() works in the blade
        foreach ($groupedRequests as $key => $itemArray) {
            $groupedRequests[$key] = collect($itemArray);
        }

        $data = compact(
            'groupedRequests',
            'totalRequest',
            'totalPednding',
            'totalSub',
            'requests',
            'account'   // pass current session account so blade can compare
        );

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.viewRequest', $data);
        }
        if (!empty($user->levelStatus)) {
            return view('user.viewRequest', $data);
        }
    }

    // ── Approve All ─────────────────────────────────────────────────────────
    public function approveAll(Request $request) {
        $requestName = $request->input('requestName');

        $items = itemRequestModel::where('requestName', $requestName)
            ->where('supplierName', getSessionAccountName())
            ->where('status', 'Submitted')
            ->get();

        $approvedCount = 0;

        foreach ($items as $item) {
            // ✅ Check stock availability FIRST
            $get = productsModel::where('product_id', $item->productId)
                ->where('account', getSessionAccountName())
                ->first();

            if (!$get || $get->quantity < $item->quantity) {
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
            $sale->account      = getSessionAccountName();
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
            $receiving->supplier  = getSessionAccountName();
            $receiving->status    = 'Not Approved';
            $receiving->save();
        }

        $log              = new logModal();
        $log->title       = 'Item Request Logs';
        $log->description = 'All items in request ' . $requestName . ' approved by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'All items approved successfully');
    }

    // ── Create item request ──────────────────────────────────────────────────
    public function itemRequest(Request $request) {
        $pId              = $request->input('pId');
        $quantity         = $request->input('pQuantity');
        $price            = $request->input('pPrice');
        $paymentType      = $request->input('paymentType', 'credit'); // Default to 'credit'
        $assignedTo       = $request->input('assignedTo'); // User to assign the request to (can be null)
        $requestDate      = $request->input('requestDate');

        \Log::info('itemRequest inputs:', $request->all());
        \Log::info('Session account: ' . getSessionAccountName());
        \Log::info('Session username: ' . session('username'));

        if (empty($pId)) {
            return redirect()->back()->with('error', 'Please select a product');
        }

        $activeRequest = itemRequestModel::where('account', getSessionAccountName())
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

        $username = session('username');
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
            'account'       => getSessionAccountName(),
            'served_by'     => $username,
            'status'        => 'Pending',
            'created_at'    => $createdAt,
        ]);

        \Log::info('Created item request:', [
            'id'            => $insert->id ?? 'N/A',
            'requestName'   => $requestName,
            'productId'     => $pId,
            'quantity'      => $quantity,
            'price'         => $price,
            'totalPrice'    => $totalPrice,
            'payment_type'  => $paymentType,
            'assigned_to'   => $assignedTo,
            'assigned_by'   => $username,
            'account'       => getSessionAccountName(),
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

        $item = itemRequestModel::where('account', getSessionAccountName())
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

        $item = itemRequestModel::where('account', getSessionAccountName())
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

    // ── Save supplier info ───────────────────────────────────────────────────
    public function saveInfo(Request $request) {
        $requestName = $request->input('requestName');
        $cname       = $request->input('Cname');
        $cphone      = $request->input('Cphone');
        $assignedTo = $request->input('assignedTo');

        \Log::info('saveInfo inputs:', $request->all());
        \Log::info('Session account: ' . getSessionAccountName());

          if (!empty($assignedTo)) {
            $updated2 = itemRequestModel::where('account', getSessionAccountName())
            ->where('requestName', $requestName)
            ->update([
                'assigned_to' => $assignedTo,
            ]);

            if($updated2) {
             return redirect()->back()->with('success', 'Allocation info saved');

            }
        }
        // If Cname/Cphone not provided directly, try selectedCustomer
        if (empty($cname)) {
            $selected = $request->input('selectedCustomer');
            if (!empty($selected)) {
                $parts = explode('|', $selected);
                $cname = $parts[0] ?? null;
                $cphone = $parts[1] ?? null;
            }
        }

        // Validate we have at least a supplier name
        if (empty($cname)) {
            return redirect()->back()->with('error', 'Please select a supplier');
        }

        // Update all items in this request for this account
        $updated = itemRequestModel::where('account', getSessionAccountName())
            ->where('requestName', $requestName)
            ->update([
                'supplierName' => $cname,
                'supplierId'   => $cphone,
            ]);

        \Log::info('saveInfo result:', [
            'requestName' => $requestName,
            'supplierName' => $cname,
            'supplierId' => $cphone,
            'rows_updated' => $updated
        ]);

        if ($updated > 0) {
            $log              = new logModal();
            $log->title       = 'Item Request Logs';
            $log->description = 'Supplier info saved for request ' . $requestName . ' by ' . session('username') . ' - Supplier: ' . $cname;
            $log->save();
            return redirect()->back()->with('success', 'Supplier info saved');
        }

        return redirect()->back()->with('error', 'Failed to save supplier info. No items found for this request.');
    }

    // ── Submit (payout) ──────────────────────────────────────────────────────
    public function payout(Request $request) {
        $requestName      = $request->input('OrdersIds');
        $requestDatePicker = $request->input('requestDatePicker');

        $updated = itemRequestModel::where('account', getSessionAccountName())
            ->where('requestName', $requestName)
            ->update(['status' => 'Submitted', 'created_at' => $requestDatePicker]);

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
        $productId = $request->input('product_id');
        $requestName = $request->input('requestName');

        // ✅ FIX: GET ALL matching items not just FIRST one
        $items = itemRequestModel::where('supplierName', getSessionAccountName())
            ->where('productId', $productId)
            ->where('requestName', $requestName)
            ->where('status', 'Submitted')
            ->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'Item not found or already processed');
        }

        $approvedCount = 0;

        foreach ($items as $item) {
            // ✅ Check stock availability FIRST before approving
            $get = productsModel::where('product_id', $item->productId)
                ->where('account', getSessionAccountName())
                ->first();

            if (!$get || $get->quantity < $item->quantity) {
                continue; // skip this item if out of stock
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
            $sale->account      = getSessionAccountName();
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
            $receiving->supplier  = getSessionAccountName();
            $receiving->status    = 'Not Approved';
            $receiving->save();

            $approvedCount++;
        }

        $log              = new logModal();
        $log->title       = 'Item Request Logs';
        $log->description = $approvedCount . ' items for product ' . $productId . ' approved by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', $approvedCount . ' items approved successfully');
    }

    // ── Reject single item ───────────────────────────────────────────────────
    public function rejectRequest(Request $request) {
        $productId = $request->input('product_id');

        $item = itemRequestModel::where('supplierName', getSessionAccountName())
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
        $productId = $request->input('product_id');

        $item = itemRequestModel::where('supplierName', getSessionAccountName())
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
        $itemId  = $req->input('itemId');
        $reqName = $req->input('reqName');

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