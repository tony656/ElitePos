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
use function getSessionAccountName;
use function getSessionAccountId;

class vendorController extends Controller
{
    public function index(Request $req) {
        $user = Auth::user();
        $Account = getSessionAccountName();

        // Get shops based on user access
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admin can see all shops
            $shops = accountModel::orderBy('name', 'asc')->get();
        } else {
            // Non-admin: only show shops they have access to
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            
            if (empty($assignedAccountIds)) {
                $shops = collect();
            } else {
                $shops = accountModel::whereIn('id', $assignedAccountIds)
                            ->orderBy('name', 'asc')
                            ->get();
            }
        }

        // Determine selected shop (for admin filter)
        $selectedShopId = $req->input('shop');
        $selectedShopName = null;
        if ($selectedShopId) {
            $selectedShop = accountModel::find($selectedShopId);
            if ($selectedShop) {
                $selectedShopName = $selectedShop->name;
            }
        }

        // Build query
        $query = vendorModal::query();

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admin: filter by selected shop if provided, else show all
            if ($selectedShopName) {
                $query->where('account', $selectedShopName);
            }
        } else {
            // Non-admin: only show vendors from their assigned shop(s)
            $query->where('account', $Account);
        }

        $fetch = $query->get();

        $data = compact(
            'fetch',
            'shops',
            'selectedShopId'
        );

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.vendors', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.vendors', $data);
        }
    }
    


    public function newVendor(Request $req) {

                $Account = getSessionAccountName();

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
        $insert->createdBy = session('username');
        $insert->account = $Account;
        $insert->save();

        if($insert) {

            $create = new logModal();
            $create->title = 'Supplier Created';
            $create->description = $name.'(Supplier) created by '.session('username');
            $create->save();

            return redirect()->back()->with('success', 'Supplier added successfully');
        } else {
              $create = new logModal();
            $create->title = 'Supplier Creation Failed';
            $create->description = $name.'(Supplier) creation Failed, by '.session('username');
            $create->save();

        return redirect()->back()->with('success', 'faild to add Supplier');

        }
    }

    public function dltVendeor(Request $req) {

                $Account = getSessionAccountName();

        $prodId = $req->input('product_id');

         $deletes = vendorModal::where('account', $Account)->where('id', $prodId)->first();

        $delete = vendorModal::where('account', $Account)->where('id', $prodId)->delete();

        if($delete) {
             $create = new logModal();
            $create->title = 'Supplier Deleted';
            $create->description = $deletes->name .'(Supplier) Deleted By '.session('username');
            $create->save();
            
            return redirect()->back()->with('success', 'Supplier Deleted successfully');
        } else {
             $create = new logModal();
            $create->title = 'Supplier Deletion Failed';
            $create->description = $deletes->name .'(Supplier) Deletion Failed By '.session('username');
            $create->save();
            
            return redirect()->back()->with('success', 'Supplier failed to delete');
        }
    }

    public function viewVendor(Request $req) {

        $user = Auth::user();
        $vendorId = $req->input('vendorId');
        $Account = getSessionAccountName();

        $fetchProduct = productsModel::where('supplier', $vendorId)->where('account', $Account)->get();
        $fetch = vendorModal::where('id', $vendorId)->where('account', $Account)->first();

            $data = compact(
        'fetch','fetchProduct'
    );

 if (strtolower(trim($user->levelStatus)) === 'admin') {
        return view('admin.vendorView', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.vendorView', $data);
    }
    }
public function supplierCredit(Request $req) {

    $user = Auth::user();
    $AccountId = getSessionAccountId();

    // Get shops based on user access
    if (strtolower(trim($user->levelStatus)) === 'admin') {
        // Admin can see all shops
        $shops = accountModel::orderBy('name', 'asc')->get();
        // Admin can filter by selected shop or show all
        $selectedShopId = $req->input('shop');
    } else {
        // Non-admin: only show shops they have access to
        $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
        
        if (empty($assignedAccountIds)) {
            $shops = collect();
        } else {
            $shops = accountModel::whereIn('id', $assignedAccountIds)
                        ->orderBy('name', 'asc')
                        ->get();
        }
        // Non-admin always uses their assigned shop(s)
        $selectedShopId = null;
    }

    // Build query with shop filter - only show debt/credit items (isDebt = 1)
    $query = recevingModel::where('isDebt', 1);

    if (strtolower(trim($user->levelStatus)) === 'admin') {
        if ($selectedShopId) {
            $query->where('account', $selectedShopId);
        }
        // If no shop selected, admin sees all shops (no account filter)
    } else {
        // Non-admin: filter by their assigned shop(s)
        if (!empty($assignedAccountIds)) {
            $query->whereIn('account', $assignedAccountIds);
        } else {
            // No shops assigned, return empty result
            $groupedCredits = collect();
            $data = compact('groupedCredits', 'shops', 'selectedShopId');
            
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                return view('admin.deptors', $data);
            }
            if(!empty($user->levelStatus)) {
                return view('user.deptors', $data);
            }
        }
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
    $credits = $query->selectRaw('MAX(receivingId) as receivingId,DATE(created_at) as order_date, supplier, SUM(quantity) as quantity, SUM(price * quantity) as total_price')
        ->groupBy('order_date', 'supplier')
        ->orderBy('order_date', 'desc')
        ->get();

    // Group by date for easier display
    $groupedCredits = $credits->groupBy('order_date');

    // Initialize products array
    $products = collect();

    $data = compact('groupedCredits', 'shops', 'selectedShopId');

    if (strtolower(trim($user->levelStatus)) === 'admin') {
        return view('admin.deptors', $data);
    }

    if(!empty($user->levelStatus)) {
        return view('user.deptors', $data);
    }
}
public function supplierItems(Request $req)
{
    $user = Auth::user();
    
    // Build base query
    $query = recevingModel::where('supplier', $req->supplier)
        ->where('isDebt', 1);

    // Apply account filter based on user role (same logic as supplierCredit)
    if (strtolower(trim($user->levelStatus)) === 'admin') {
        $selectedShopId = $req->input('shop') ?? $req->input('account'); // Try both parameters
        if ($selectedShopId) {
            $query->where('account', $selectedShopId);
        }
        // If no shop selected, admin sees all shops (no account filter)
    } else {
        // Non-admin: filter by their assigned shop(s)
        $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
        if (!empty($assignedAccountIds)) {
            $query->whereIn('account', $assignedAccountIds);
        } else {
            // No shops assigned, return empty result
            return response()->json([]);
        }
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
            $payment->receivingsId = $orderid;
            $payment->account = getSessionAccountName();
            $payment->save();

            $check = madeni::where('receivingsId', $orderid)->sum('amount');

            if(!$check >= $receivings) {
                $receiving->update([
                    'paid_status' => 'Completed',
                ]);
            } else {
                $receiving->update([
                    'paid_status' => 'Partial',
                ]);
            }
            // Check if payment is complete
            $totalPaidForOrder = madeni::where('receivingsId', $orderid)->sum('amount');

            if ($totalPaidForOrder >= $receivings) {
                // Payment is complete, update receiving
                $receiving->update([
                    'isPaid' => 1,
                    'isDebt' => 0
                ]);
            }

            // Log the transaction
            $logEntry = new logModal();
            $logEntry->title = 'Supplier Payment';
            $logEntry->description = $supplier . ' (Supplier) made a payment of ' . $amountPaid . ' for order ' . $orderid . ' by ' . session('username');
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
                $logEntry->description = 'Supplier credit for ' . $supplier . ' on ' . $date . ' was deleted by ' . session('username');
                $logEntry->save();

                return redirect()->back()->with('success', 'Supplier credit deleted successfully');
            }

            return redirect()->back()->with('error', 'No records found to delete');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
}
