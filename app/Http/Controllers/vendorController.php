<?php

namespace App\Http\Controllers;
use App\Models\recevingModel;
use App\Models\vendorModal;
use App\Models\accountModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\logModal;
use App\Models\productsModel;
use App\Models\madeni;
use App\Models\UserAccount;
use function getCurrentShopId;
use function getUserAccounts;

class vendorController extends Controller
{
    public function index(Request $req) {

    if (!canUser('view_suppliers')) {
        abort(403, 'Unauthorized access');
    }
        $user = Auth::user();
        $Account = getCurrentShopId();
        $shops = getUserAccounts();


        // Determine selected shop (for admin filter)
        $selectedShopId = $req->input('shop');
   

        // Build query
        $query = vendorModal::query();

            if ($selectedShopId) {
                $query->where('account', $selectedShopId);
            }
        
        $fetch = $query->get();

        $data = compact(
            'fetch',
            'shops',
        );

            return view('vendors', $data);
    
    }
    


    public function newVendor(Request $req) {

        $Account = getCurrentShopId();

        $name = $req->input('name');
        $contact = $req->input('contact');
        $address = $req->input('address');
        $type = $req->input('type');
        $credit = $req->input('credit');
        $bank = $req->input('bank');
        $account = $req->input('account');
        $description = $req->input('description');

        $insert = new vendorModal();
        $insert->name = $name;
        $insert->location = $address;
        $insert->contact = $contact;
        $insert->description = $description;
        $insert->businessType = $type;
        $insert->credit = $credit;
        $insert->bank = $bank;
        $insert->card = $account;
        $insert->createdBy = Auth::user()->name;
        $insert->account = 7;
        $insert->save();

        if($insert) {

            $create = new logModal();
            $create->title = 'Supplier Created';
            $create->description = $name.'(Supplier) created by '.Auth::user()->name;
            $create->save();

            return redirect()->back()->with('success', 'Supplier added successfully');
        } else {
              $create = new logModal();
            $create->title = 'Supplier Creation Failed';
            $create->description = $name.'(Supplier) creation Failed, by '.Auth::user()->name;
            $create->save();

        return redirect()->back()->with('success', 'faild to add Supplier');

        }
    }

    public function dltVendeor(Request $req) {

                $Account = getCurrentShopId();

        $prodId = $req->input('product_id');

         $deletes = vendorModal::where('id', $prodId)->first();

        $delete = vendorModal::where('id', $prodId)->delete();

        if($delete) {
             $create = new logModal();
            $create->title = 'Supplier Deleted';
            $create->description = $deletes->name .'(Supplier) Deleted By '.Auth::user()->name;
            $create->save();
            
            return redirect()->back()->with('success', 'Supplier Deleted successfully');
        } else {
                        
            return redirect()->back()->with('success', 'Supplier failed to delete');
        }
    }

    public function viewVendor(Request $req) {

        $user = Auth::user();
        $vendorId = $req->input('vendorId');
        $Account = getCurrentShopId();

        $fetchProduct = productsModel::where('supplier', $vendorId)->get();
        $fetch = vendorModal::where('id', $vendorId)->first();

            $data = compact(
        'fetch','fetchProduct'
    );

        return view('vendorView', $data);

    }
public function supplierCredit(Request $req) {
if (!canUser('manage_supplier_credit')) {
        abort(403, 'Unauthorized access');
    }
    $user = Auth::user();
        $selectedShopId = $req->input('shop');
        $shops = getUserAccounts();
        $shopIds = array_column(getUserAccounts(),'id');

        if(!empty($selectedShopId)) {
            session([
                'selected_shop_id' => getCurrentShopId()
            
            ]);
        } else {
            $selectedShopId = getCurrentShopId();
        }

    // Build query with shop filter - only show debt/credit items (isDebt = 1)
    $query = recevingModel::where('isDebt', 1);
            $query->whereIn('account', $shopIds);
  if ($selectedShopId) {
            $query->where('account', $selectedShopId);
        }
  
    // Filter by date range - default to today if no dates provided
    if ($req->has('date_from') && !empty($req->date_from)) {
        $query->whereDate('created_at', '>=', $req->date_from);
    } else {
        // Default to today's date if no date_from provided
        $query->whereDate('created_at', '>=', date('Y-m-d'));
    }
    
    if ($req->has('date_to') && !empty($req->date_to)) {
        $query->whereDate('created_at', '<=', $req->date_to);
    } else {
        // Default to today's date if no date_to provided
        $query->whereDate('created_at', '<=', date('Y-m-d'));
    }

    // Get supplier credits grouped by date and supplier
    $credits = $query->selectRaw('MAX(receivingId) as receivingId,MAX(receivingName) as receivingName,DATE(created_at) as order_date,account, supplier, SUM(quantity) as quantity, SUM(price * quantity) as total_price')
        ->groupBy('order_date', 'supplier')
        ->groupBy('order_date', 'account')
        ->orderBy('order_date', 'desc')
        ->get();

    // Group by date for easier display
    $groupedCredits = $credits->groupBy('order_date');

    // Initialize products array
    $products = collect();

    $data = compact('groupedCredits', 'shops', 'selectedShopId');


        return view('deptors', $data);

}

public function mainCredit(Request $req) {

    $user = Auth::user();
  
    $selectedShopId = 7;
        

    // Build query with shop filter - only show debt/credit items (isDebt = 1)
    $query = recevingModel::where('isDebt', 1);
  if ($selectedShopId) {
            $query->where('account', $selectedShopId);
        }
  
    // Filter by date range - default to today if no dates provided
    if ($req->has('date_from') && !empty($req->date_from)) {
        $query->whereDate('created_at', '>=', $req->date_from);
    } else {
        // Default to today's date if no date_from provided
        $query->whereDate('created_at', '>=', date('Y-m-d'));
    }
    
    if ($req->has('date_to') && !empty($req->date_to)) {
        $query->whereDate('created_at', '<=', $req->date_to);
    } else {
        // Default to today's date if no date_to provided
        $query->whereDate('created_at', '<=', date('Y-m-d'));
    }

    // Get supplier credits grouped by date and supplier
    $credits = $query->selectRaw('MAX(receivingId) as receivingId,MAX(receivingName) as receivingName,DATE(created_at) as order_date,account, supplier, SUM(quantity) as quantity, SUM(price * quantity) as total_price')
        ->groupBy('order_date', 'supplier')
        ->groupBy('order_date', 'account')
        ->orderBy('order_date', 'desc')
        ->get();

    // Group by date for easier display
    $groupedCredits = $credits->groupBy('order_date');

    // Initialize products array
    $products = collect();

    $data = compact('groupedCredits', 'selectedShopId');


        return view('main-credit', $data);

}


public function dltSupPay(Request $req) {
    $id = $req->input('id');

    $query = madeni::where('id', $id)->delete();

    if($query) {
        return redirect()->back()->with('success', "Payment Deleted Successfully");
    } else {
        return redirect()->back()->with('error', "Failed to Delete Payment");
    }
}

public function suplierPayments (Request $req) {
        $user = Auth::user();
        $selectedShopId = $req->input('shop');
        $date_from = $req->date_from ?? date('Y-m-d');
        $date_to = $req->date_to ?? date('Y-m-d');
        $shops = getUserAccounts();
        
        if(!empty($selectedShopId)) {
            session([
                'selected_shop_id' => getCurrentShopId()
            
            ]);
        } else {
            $selectedShopId = getCurrentShopId();
        }
   
 // Filter by date range - default to today if no dates provided


    // Build query with shop filter - only show debt/credit items (isDebt = 1)
    $querys = madeni::query();
    if(!empty($selectedShopId)) {
    $querys->where('account', $selectedShopId);
    }
    $querys->whereDate('created_at', '>=', $date_from)->whereDate('created_at', '<=', $date_to);

    $query = $querys->get();
    
       $data = compact('query', 'shops', 'selectedShopId');

        return view('supplier-payments', $data);

}

public function mainPaid (Request $req) {
        $user = Auth::user();
        $selectedShopId = $req->input('shop');
        $date_from = $req->date_from ?? date('Y-m-d');
        $date_to = $req->date_to ?? date('Y-m-d');
        $shops = getUserAccounts();
        
            $selectedShopId = 7;
        

    // Build query with shop filter - only show debt/credit items (isDebt = 1)
    $query = madeni::where('account', $selectedShopId)->whereDate('created_at', '>=', $date_from)->whereDate('created_at', '<=', $date_to)->get();


       $data = compact('query', 'shops', 'selectedShopId');

        return view('main-paid', $data);

}

public function supplierItems(Request $req)
{
    $user = Auth::user();
    
    // Build base query
    $query = recevingModel::where('supplier', $req->supplier)
        ->where('isDebt', 1);

    // Apply account filter based on user role (same logic as supplierCredit)
        $selectedShopId = $req->input('shop') ?? $req->input('account'); // Try both parameters
        if ($selectedShopId) {
            $query->where('account', $selectedShopId);
        }
  

    // Filter by date range - default to today if no dates provided
    if ($req->has('date_from') && !empty($req->date_from)) {
        $query->whereDate('created_at', '>=', $req->date_from);
    } else {
        // Default to today's date if no date_from provided
        $query->whereDate('created_at', '>=', date('Y-m-d'));
    }
    
    if ($req->has('date_to') && !empty($req->date_to)) {
        $query->whereDate('created_at', '<=', $req->date_to);
    } else {
        // Default to today's date if no date_to provided
        $query->whereDate('created_at', '<=', date('Y-m-d'));
    }

    $prods = $query->get();

    $result = [];

    foreach ($prods as $prod) {
        $item = productsModel::where('product_id', $prod->productId)->first();

        if ($item) {
            $result[] = [
                'name01'   => $item->name01,
                'quantity' => $prod->quantity,
                'bPrice'   => (float) $prod->price,
                'supplier' => $prod->supplier,
                'created_at' => $prod->created_at
            ];
        }
    }

    return response()->json($result);
}
 public function supplierPay(Request $request) {
        $date = $request->input('date');
        $supplier = $request->input('supplier');
        $orderid = $request->input('orderid');
        $amountPaid = $request->input('amount');
        $shop = $request->input('shop');

        try {
            // Validate input
            if (!$date || !$supplier || !$orderid) {
                return redirect()->back()->with('error', 'Invalid payment data');
            }

            // Get the receiving order
            $receiving = recevingModel::where('receivingId', $orderid)
                                      ->where('supplier', $supplier)
                                      ->first();

            $receivings = recevingModel::where('receivingId', $orderid)
                                      ->where('supplier', $supplier)
                                      ->sum(DB::raw('price * quantity'));

            if (!$receiving) {
                return redirect()->back()->with('error', 'Receiving order not found');
            }

            // If amount not provided, treat as full payment
            if (!$amountPaid || $amountPaid == '') {
                $amountPaid = $receivings;
            }

            // Validate amount
            if ($amountPaid <= 0) {
                return redirect()->back()->with('error', 'Payment amount must be greater than 0');
            }

            // Create madeni (payment) record
            $payment = new madeni();
            $payment->supplierId = $receiving->supplier; // Or use actual supplier ID if available
            $payment->amount = $amountPaid;
            $payment->receivingsId = $receiving->receivingId ?? $orderid;
            $payment->account = $shop ?? '';
            $payment->save();


            // Log the transaction
            $logEntry = new logModal();
            $logEntry->title = 'Supplier Payment';
            $logEntry->description = $supplier . ' (Supplier) made a payment of ' . $amountPaid . ' for order ' . $orderid . ' by ' . Auth::user()->name;
            $logEntry->save();

            return redirect()->back()->with('success', 'Payment of ' . number_format($amountPaid, 2) . ' recorded successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete debt record
     */
    public function deleteDebt(Request $request) {
        $date = $request->input('date');
        $supplier = $request->input('supplier');
        $start_date = date('Y-m-d 00:00:00', strtotime($date));
        $end_date = date('Y-m-d 23:59:59', strtotime($date));

        try {
            $deleted = recevingModel::where('supplier', $supplier)
                                    ->whereBetween('created_at', [$start_date, $end_date])
                                    ->delete();

            if ($deleted > 0) {
                // Log the deletion
                $logEntry = new logModal();
                $logEntry->title = 'Debt Deleted';
                $logEntry->description = 'Supplier credit for ' . $supplier . ' on ' . $date . ' was deleted by ' . Auth::user()->name;
                $logEntry->save();

                return redirect()->back()->with('success', 'Supplier credit deleted successfully');
            }

            return redirect()->back()->with('error', 'No records found to delete');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
}
