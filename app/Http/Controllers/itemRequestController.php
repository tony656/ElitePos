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

class itemRequestController extends Controller
{
    public function index() {
        $user = Auth::user();

        $products = productsModel::where('account', session('account'))->get();

        // Get the last active item request for the current user
        $orders = itemRequestModel::where('account', session('account'))
            ->where('served_by', session('username'))
            ->where('status', '!=', 'Submitted')
            ->orderBy('id', 'desc')
            ->first();

            $data = compact(
        'products','orders'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.item_request', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.item_request', $data);
    }
    }

public function viewRequest() {
    $user = Auth::user();
    $account = session('account');

    // Get requests from both perspectives
    $requests = itemRequestModel::where(function($query) use ($account) {
            // Get requests created by this account
            $query->where('account', $account);
        })
        ->orWhere(function($query) use ($account) {
            // Get requests where this account is the supplier
            $query->where('supplierName', $account)
                  ->whereNotNull('supplierName');
        })
        ->orderBy('created_at', 'desc')
        ->get();

    // Get product names for all items
    $productIds = $requests->pluck('productId')->unique();
    $products = productsModel::whereIn('product_id', $productIds)->get()->keyBy('product_id');

    // Add product names to requests
    foreach ($requests as $request) {
        $request->productName = $products[$request->productId]->name01 ?? 'Unknown Product';
    }

    // Group requests by requestName
    $groupedRequests = [];
    foreach ($requests as $request) {
        $requestId = $request->requestName;
        if (!isset($groupedRequests[$requestId])) {
            $groupedRequests[$requestId] = [];
        }
        $groupedRequests[$requestId][] = $request;
    }

    // Calculate statistics
    $totalRequest = count($groupedRequests);
    
    // Count pending requests (unique request IDs with any item pending)
    $pendingRequests = [];
    foreach ($requests as $request) {
        if ($request->status === 'Pending') {
            $pendingRequests[$request->requestName] = true;
        }
    }
    $totalPednding = count($pendingRequests);
    
    // Count submitted requests (unique request IDs with any item submitted)
    $submittedRequests = [];
    foreach ($requests as $request) {
        if ($request->status === 'Submitted') {
            $submittedRequests[$request->requestName] = true;
        }
    }
    $totalSub = count($submittedRequests);

    $data = compact(
        'groupedRequests', 
        'totalRequest', 
        'totalPednding', 
        'totalSub',
        'requests'
    );

    if ($user->levelStatus === 'Admin') {
        return view('admin.viewRequest', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.viewRequest', $data);
    }
}

// Add this method to handle approve all
public function approveAll(Request $request) {
    $requestName = $request->input('requestName');
    
    $items = itemRequestModel::where('requestName', $requestName)
        ->where('supplierName', session('account'))
        ->where('status', 'Submitted')
        ->get();

    foreach ($items as $item) {
        // Update status to Approved
        $item->status = 'Approved';
        $item->save();

        // Process each item (similar to single approve logic)
        $get = productsModel::where('product_id', $item->productId)
            ->where('account', 'Loliondo SHop')
            ->first();

        if($get) {
            $get->quantity -= $item->quantity;
            $get->save();

            $log = new logModal();
            $log->title = 'Stock Log';
            $log->description =  $get->name01.' Stock Deducted '. $item->quantity .' By '.session('username'). ' went to another shop';
            $log->save();
        }

        // Create sales report
        $sale = new salsModel();
        $sale->sales_id = $item->requestName . '-' . $item->productId;
        $sale->salesName = $item->requestName;
        $sale->stockId = $item->productId;
        $sale->cName = 'Inter-Shop Sale';
        $sale->cPhone = '';
        $sale->productId = $item->productId;
        $sale->pQuantity = $item->quantity;
        $sale->productPrice = $get->sPrice;
        $sale->totalPrice = $item->price;
        $sale->served_by = Auth::user()->name;
        $sale->account = session('account');
        $sale->save();

        // Add to receiving waiting for approval
        $receiving = new recevingModel();
        $receiving->productId = $item->productId;
        $receiving->quantity = $item->quantity;
        $receiving->price = $item->price;
        $receiving->account = $item->account;
        $receiving->served_by = Auth::user()->name;
        $receiving->supplier = session('account');
        $receiving->status = 'Not Approved';
        $receiving->save();
    }

    $log = new logModal();
    $log->title = 'Item Request Logs';
    $log->description = 'All items in request ' . $requestName . ' approved by ' . session('username');
    $log->save();

    return redirect()->back()->with('success', 'All items in request approved successfully');
}

    public function itemRequest(Request $request) {

        $pId = $request->input('pId');
        $quantity = $request->input('pQuantity');
        $price = $request->input('pPrice');
        $totalPrice = $request->input('totalPrice');



    if (empty($pId)) {
        return redirect()->back()->with('success', 'Add a product to create an order');
    }

    // Check for existing active item request for the current user
    $activeRequest = itemRequestModel::where('account', session('account'))
        ->where('status', 'Pending')
        ->orderBy('id', 'desc')
        ->first();

    if ($activeRequest) {
        $requestName = $activeRequest->requestName;
    } else {
        $requestName = date('Ymd') . '-' . str_pad(
            itemRequestModel::whereDate('created_at', date('Y-m-d'))->count() + 1,
            4, '0', STR_PAD_LEFT
        );
    }
    $username = session('username');
    if(empty($username)) {
        return redirect()->back()->with('success', 'session username is empty');
    }
    $insert = itemRequestModel::create([
        'requestName' => $requestName,
        'productId' => $pId,
        'quantity' => $quantity,
        'price' => $totalPrice,
        'account' => session('account'),
        'served_by' => $username,
        'status' => 'Pending'
    ]);
    if ($insert) {
        $create = new logModal();
            $create->title = 'Item Request Logs';
            $create->description = $insert->requestName.' Item Request Created By '.session('username');
            $create->save();
        return redirect()->back()->with('success', 'Item Request Created');
    } else {
        return redirect()->back()->with('error', 'Failed to create item request');
    }
    }
    public function updQuant(Request $request) {
        $requestName = $request->input('OrdersIds');
        $prodId = $request->input('prodId');
        $prodQuantity = $request->input('prodQuantity');

        $item = itemRequestModel::where('account', session('account'))
            ->where('requestName', $requestName)
            ->where('productId', $prodId)
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found');
        }

        $item->quantity = $prodQuantity;
        $item->totalPrice = ($prodQuantity * $item->price) - $item->discount;
        $item->save();

        $create = new logModal();
        $create->title = 'Item Request Logs';
        $create->description = 'Quantity updated for item ' . $prodId . ' in request ' . $requestName . ' by ' . session('username');
        $create->save();

        return redirect()->back()->with('success', 'Quantity updated successfully');
    }

    public function dltProdOrd(Request $request) {
        $requestName = $request->input('OrdersIds');
        $prodId = $request->input('prodId');

        $item = itemRequestModel::where('account', session('account'))
            ->where('requestName', $requestName)
            ->where('productId', $prodId)
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found');
        }

        $item->delete();

        $create = new logModal();
        $create->title = 'Item Request Logs';
        $create->description = 'Item ' . $prodId . ' deleted from request ' . $requestName . ' by ' . session('username');
        $create->save();

        return redirect()->back()->with('success', 'Item removed successfully');
    }

    public function saveInfo(Request $request) {
        $requestName = $request->input('requestName');
        $cname = $request->input('Cname');
        $cphone = $request->input('Cphone');

        if (empty($cname)) {
            $selected = $request->input('selectedCustomer');
            if (!empty($selected)) {
                list($cname, $cphone) = explode('|', $selected);
            }
        }

        // Update all items in the request with supplier info
        $updated = itemRequestModel::where('account', session('account'))
            ->where('requestName', $requestName)
            ->update([
                'supplierName' => $cname,
                'supplierId' => $cphone
            ]);

        if ($updated > 0) {
            $create = new logModal();
            $create->title = 'Item Request Logs';
            $create->description = 'Supplier info saved for request ' . $requestName . ' by ' . session('username');
            $create->save();
            return redirect()->back()->with('success', 'Supplier info saved');
        } else {
            return redirect()->back()->with('error', 'Failed to save supplier info');
        }
    }

    public function payout(Request $request) {
        $requestName = $request->input('OrdersIds');

        // Update status of all items in the request
        $updated = itemRequestModel::where('account', session('account'))
            ->where('requestName', $requestName)
            ->update(['status' => 'Submitted']);

        if ($updated > 0) {
            $create = new logModal();
            $create->title = 'Item Request Logs';
            $create->description = 'Item request ' . $requestName . ' submitted by ' . session('username');
            $create->save();
            return redirect()->back()->with('success', 'Item request submitted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to submit item request');
        }
    }

    public function approveRequest(Request $request) {
        $productId = $request->input('product_id');

        $item = itemRequestModel::where('supplierName', session('account'))
            ->where('productId', $productId)
            ->where('status', 'Submitted')
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found or already processed');
        }

        // Update status to Approved
        $item->status = 'Approved';
        $item->save();

    
            $get = productsModel::where('product_id', $item->productId)
                ->where('account', 'Loliondo SHop')
                ->first();

                if($get) {
                    $get->quantity -= $item->quantity;
                    $get->save();

            $create = new logModal();
            $create->title = 'Stock Log';
            $create->description =  $get->name01.' Stock Deducted '. $get->quantity .' By '.session('username'). 'went to another shop';
            $create->save();

                }
        
        // Create sales report
        $sale = new salsModel();
        $sale->sales_id = $item->requestName . '-' . $productId;
        $sale->salesName = $item->requestName;
        $sale->stockId = $item->productId;
        $sale->cName = 'Inter-Shop Sale'; // Placeholder
        $sale->cPhone = '';
        $sale->productId = $item->productId;
        $sale->pQuantity = $item->quantity;
        $sale->productPrice = $item->price;
        $sale->totalPrice = $item->quantity * $item->price;
        $sale->served_by = Auth::user()->name;
        $sale->account = session('account');
        $sale->save();

        // Add to receiving waiting for approval
        $receiving = new recevingModel();
        $receiving->productId = $item->productId;
        $receiving->quantity = $item->quantity;
        $receiving->price = $item->price;
        $receiving->account = $item->account; // Assuming same account for now, adjust for inter-shop
        $receiving->served_by = Auth::user()->name;
        $receiving->supplier = session('account');
        $receiving->status = 'Not Approved';
        $receiving->save();

        $log = new logModal();
        $log->title = 'Item Request Logs';
        $log->description = 'Item ' . $productId . ' approved and sold by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Item approved and processed for sale');
    }

    public function rejectRequest(Request $request) {
        $productId = $request->input('product_id');

        $item = itemRequestModel::where('supplierName', session('account'))
            ->where('productId', $productId)
            ->where('status', 'Submitted')
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found or already processed');
        }

        $item->status = 'Rejected';
        $item->save();

        $log = new logModal();
        $log->title = 'Item Request Logs';
        $log->description = 'Item ' . $productId . ' rejected by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Item rejected');
    }

    public function outOfStockRequest(Request $request) {
        $productId = $request->input('product_id');

        $item = itemRequestModel::where('supplierName', session('account'))
            ->where('productId', $productId)
            ->where('status', 'Submitted')
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found or already processed');
        }

        $item->status = 'Out of Stock';
        $item->save();

        $log = new logModal();
        $log->title = 'Item Request Logs';
        $log->description = 'Item ' . $productId . ' marked as out of stock by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Item marked as out of stock');
    }

    public function dltItemReq(Request $req) {
        $itemId = $req->input('itemId');
        $reqName = $req->input('reqName');

        $dlt = itemRequestModel::where('requestName', $reqName)->where('productId', $itemId)->delete();

        if($dlt) {
            return redirect()->back()->with('success', 'Item removed');
        } else {
            return redirect()->back()->with('error', 'Item not removed');
        }
    }
}


