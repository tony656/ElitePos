<?php

namespace App\Http\Controllers;
use App\Models\logModal;
use Illuminate\Http\Request;
use App\Models\customerModel;
use Illuminate\Support\Str;
use App\Models\salsModel;
use App\Models\productsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\usersModel;
use App\Models\accountModel;
use function getSessionAccountId;

class customerController extends Controller
{

    
    public function index(Request $request)
    {
        $user = Auth()->user();

        // For admin: allow account filtering from request, otherwise use session account name
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $selectedAccount = $request->query('account', getSessionAccountId());
            // Fetch all accounts for admin dropdown
            $accounts = accountModel::all();
        } else {
            $selectedAccount = getSessionAccountId();
            $accounts = collect(); // Empty collection for non-admin
        }

        $fetch = customerModel::where('account', $selectedAccount)->get(); // Fetch all customers from the database
        $myCustomers = customerModel::where('account', $selectedAccount)->where('employeeId', $user->id)->get();

        foreach($fetch as $customer) {
            $customer->totalSales = salsModel::where('account', $selectedAccount)->where('cPhone', $customer->id)->count();
        }

        $users = usersModel::where('account', $selectedAccount)->get();

        $data = compact('fetch', 'myCustomers', 'users', 'accounts', 'selectedAccount');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.customers', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.customers', $data);
        }
    }
    public function addCustomer(Request $request)
    {
        $user = auth()->user();

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
            'credit' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'allocation' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'account' => 'nullable|string|max:255',
        ]);

        // Determine which account to use: admin can select, others use session account name
        $selectedAccount = $request->input('account', getSessionAccountId());

        $look = customerModel::where('name', $request->input('name')
                             )->where('account', $selectedAccount)->get();

        /*if($look > 1) {
            return redirect()->back()->with('success', 'Customer with this name is available');
        }*/

        if(empty($request->input('allocation'))) {
            $id = $user->id;
        } else {
            $id = $request->input('allocation');
        }
        // Create a new customer instance
        $customer = new customerModel();
        $customer->name = $request->input('name');
        $customer->employeeId = $id;
        $customer->customerId = Str::uuid(); 
        $customer->phone = $request->input('contact');
        $customer->address = $request->input('address');
        $customer->limits = $request->input('credit', 0);
        $customer->business = $request->input('type', '');
        $customer->description = $request->input('description', 'No description');
        $customer->account = $selectedAccount;

        // Save the customer to the database
        if ($customer->save()) {

              $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $request->input('name').' (Customer) Added Successfully By '.session('username');
            $create->save();

            return redirect()->back()->with('success', 'Customer added successfully!');
        } else {
             $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $request->input('name').' (Customer) Failed to be added By '.session('username');
            $create->save();

            return redirect()->back()->with('error', 'Failed to add customer.');
        }
    }

 public function searchCustomer(Request $request)
{
  
    $query = $request->query('query', '');
    $user = Auth::user();
    
    if (strlen($query) < 1) {
        return response()->json([]);
    }

    // Get all account IDs assigned to the user
    if (strtolower(trim($user->levelStatus)) === 'admin') {
        // Admins can search all accounts or use specified account
        $accountParam = $request->query('account', getSessionAccountId());
        $accountIds = is_array($accountParam) ? $accountParam : [$accountParam];
    } else {
        // Regular users: get from user_accounts relationship
        $accountIds = $user->accounts()->pluck('account')->toArray();
        
        // Fallback to the account field in users table if no pivot records
        if (empty($accountIds)) {
            $accountIds = [$user->account];
        }
    }
    
    $customers = customerModel::whereIn('account', $accountIds)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'phone', 'limits', 'account')
            ->limit(15)
            ->get();

    return response()->json($customers);
}
 public function getCustomerDetails($customerId)
 {
     $user = Auth::user();
     
     // Get all account IDs assigned to the user
     if (strtolower(trim($user->levelStatus)) === 'admin') {
         // Admins: get all account IDs (not just session account)
         $accountIds = $user->accounts()->pluck('account')->toArray();
         if (empty($accountIds)) {
             $accountIds = [getSessionAccountId()];
         }
     } else {
         // Regular users: get from user_accounts relationship
         $accountIds = $user->accounts()->pluck('account')->toArray();
         
         // Fallback to the account field in users table if no pivot records
         if (empty($accountIds)) {
             $accountIds = [$user->account];
         }
     }
     
     $customer = DB::table('customers')
         ->whereIn('account', $accountIds)
         ->where('id', $customerId)
         ->first();

     if (!$customer) {
         return response()->json(['error' => 'Customer not found'], 404);
     }

     // Use same calculation method as $odez
     // Sum credit from orders with 'Debt' or 'partial' status
     // Filtered by both cName AND cPhone for accuracy
     $currentCredit = DB::table('orders')
         ->where('account', $customer->account)
         ->where('cName', $customer->name)
         ->where('cPhone', $customer->id)
         ->whereIn('status', ['Debt', 'Partial'])
         ->sum('credit');

     // Ensure numeric values
     $limits = (float)($customer->limits ?? 0);
     $credit = (float)($currentCredit ?? 0);
     $available = $limits - $credit;

     return response()->json([
         'id' => $customer->id,
         'name' => $customer->name,
         'phone' => $customer->phone,
         'limits' => $limits,
         'credit' => $credit,
         'available' => $available
     ]);
 }
    public function editCustomer(Request $req) {
        $req->validate([
            'customerId' => 'required|integer',
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
            'credit' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $get = customerModel::where('id', $req->input('customerId')
                             )->where('account', getSessionAccountId())->first();

        if (!$get) {
            $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = 'Customer Edit Failed - Customer not found By '.session('username');
            $create->save();

            return redirect()->back()->with('error', 'Customer not found or you do not have permission to edit this customer.');
        }

        /*if($look > 1) {
            return redirect()->back()->with('success', 'Customer with this name is available');
        }*/
        // Update customer properties
        $get->name = $req->input('name');
        $get->customerId = Str::uuid(); 
        $get->phone = $req->input('contact');
        $get->address = $req->input('address');
        $get->limits = $req->input('credit', 0);
        $get->business = $req->input('type', '');
        $get->description = $req->input('description', 'No description');
        $get->account = getSessionAccountId();

        // Save the customer to the database
        if ($get->save()) {

              $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $req->input('name').' (Customer) Edited Successfully By '.session('username');
            $create->save();

            return redirect()->back()->with('success', 'Customer Edited successfully!')->with(compact('get'));
        } else {
             $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $req->input('name').' (Customer) Failed to be Edit By '.session('username');
            $create->save();

            return redirect()->back()->with('error', 'Failed to Edit customer.');
        }

    }
    public function dltCustomer(Request $req) {

        $prodId = $req->input('name');

        $dlts = customerModel::where('account', getSessionAccountId())->where('name', $prodId)->first();
        $dlt = customerModel::where('account', getSessionAccountId())->where('name', $prodId)->delete();
        if ($dlt) {
            $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $prodId.' Customer deleted By '.session('username');
            $create->save();

            return redirect()->back()->with('success', 'Customer deleted successfully!');
        } else {
             $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $prodId.' Customer not deleted By '.session('username');
            $create->save();

            return redirect()->back()->with('error', 'Failed to delete customer.');
        }

    }

  public function details($id)
{
    $accountId = getSessionAccountId();
    $customers = salsModel::where('account', $accountId)->where('cName', $id)->get();

    if ($customers->isEmpty()) {
        return response()->json(['error' => 'Customer not found'], 404);
    }

    $results = [];

    foreach ($customers as $customer) {
        $product = productsModel::where('account', $accountId)->where('product_id', $customer->productId)->first();

        $results[] = [
            'product_name' => $product->name01 ?? 'N/A',
            'quantity' => $customer->pQuantity ?? 0,
            'price' => $customer->totalPrice ?? 0,
        ];
    }

    return response()->json($results);
}

public function customerView(Request $req) {
    $user = auth()->user();
    $products = [];
    $get = customerModel::where('name', $req->input('name'))->first();

    // If customer not found, redirect back with error
    if (!$get) {
        return redirect()->back()->with('error', 'Customer not found.');
    }

    $accountId = getSessionAccountId();
    
    // Handle date filtering - default to today
    $selectedDate = $req->input('selectedDate');
    if ($selectedDate) {
        $start_date = date('Y-m-d 00:00:00', strtotime($selectedDate));
        $end_date = date('Y-m-d 23:59:59', strtotime($selectedDate));
        $sales = salsModel::where('account', $accountId)
            ->where('cName', $req->input('name'))
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orderBy('created_at','desc')
            ->get();
    } else {
        // Default to today's sales
        $today_start = date('Y-m-d 00:00:00');
        $today_end = date('Y-m-d 23:59:59');
        $sales = salsModel::where('account', $accountId)
            ->where('cName', $req->input('name'))
            ->whereBetween('created_at', [$today_start, $today_end])
            ->orderBy('created_at','desc')
            ->get();
    }
       
    $data = compact(
        'sales','get', 'selectedDate'
    );

    if (strtolower(trim($user->levelStatus)) === 'admin') {
        return view('admin.customerView', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.customerView', $data);
    }
}

}
