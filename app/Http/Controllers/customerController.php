<?php

namespace App\Http\Controllers;
use App\Models\CustomerGroup;
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
use function getCurrentShopId;
use function getUserAccounts;

class customerController extends Controller
{

    
    public function index(Request $request)
    {
        $user = Auth()->user();
        $account = $request->query('account');
        if(!empty($account))
            {
                session(['selected_shop_id' => $account]);
            }
        $accounts = getUserAccounts();
        
        // For admin: allow account filtering from request, otherwise use session account name
            $selectedAccount = getCurrentShopId();
    
        // Fetch ALL customers from all accounts (no account filter)
        $fetch = customerModel::all();

        // myCustomers: customers assigned to this user across all accounts
        $myCustomers = customerModel::where('account', $selectedAccount)->get();

        $customerGroups = customerModel::whereNotNull('groups')
            ->where('groups', '!=', '')
            ->distinct()
            ->orderBy('groups')
            ->pluck('groups');

        $definedGroups = CustomerGroup::where('account', $selectedAccount)
            ->orderBy('name')
            ->pluck('name');

        $groups = $definedGroups->merge($customerGroups)->unique()->values()->all();

        $users = usersModel::all();

        $customerIds = $fetch->pluck('id')->toArray();

        $oldestDebtDates = DB::table('orders')
            ->whereIn('status', ['Debt', 'Partial'])
            ->whereIn('cPhone', $customerIds)
            ->select('cPhone', DB::raw('MIN(created_at) as oldest_debt_date'))
            ->groupBy('cPhone')
            ->get()
            ->keyBy('cPhone');

        $debtsAgg = DB::table('orders')
            ->whereIn('cPhone', $customerIds)
            ->whereIn('status', ['Debt', 'Partial'])
            ->select('cPhone', DB::raw('SUM(credit) as total_credit'))
            ->groupBy('cPhone')
            ->get()
            ->keyBy('cPhone');

        $paymentsAgg = DB::table('debts')
            ->whereIn('cId', $customerIds)
            ->select('cId', DB::raw('SUM(amount) as total_paid'))
            ->groupBy('cId')
            ->get()
            ->keyBy('cId');

        $badDebtorDays = [];
        foreach ($fetch as $customer) {
            $totalDebt = (float) ($debtsAgg[$customer->id]->total_credit ?? 0);
            $totalPaid = (float) ($paymentsAgg[$customer->id]->total_paid ?? 0);
            $remainingDebts[$customer->id] = max(0, $totalDebt - $totalPaid);

            if ($remainingDebts[$customer->id] > 0) {
                $oldestDate = $oldestDebtDates[$customer->id]->oldest_debt_date ?? null;
                if ($oldestDate) {
                    $daysElapsed = \Carbon\Carbon::parse($oldestDate)->diffInDays(now());
                    $threshold = (int)($customer->bad_debtor ?? 0);
                    $badDebtorDays[$customer->id] = $threshold > 0 ? $threshold - $daysElapsed : null;
                } else {
                    $badDebtorDays[$customer->id] = null;
                }
            } else {
                $badDebtorDays[$customer->id] = null;
            }
        }

        $data = compact('fetch', 'myCustomers', 'users', 'accounts', 'selectedAccount', 'groups', 'remainingDebts', 'badDebtorDays');

            return view('customers', $data);
    
    }

    public function getGroups(Request $request)
    {
        $accountId = getCurrentShopId();
        $groups = CustomerGroup::where('account', $accountId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($groups);
    }

    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $accountId = getCurrentShopId();
        $group = CustomerGroup::create([
            'name' => $request->input('name'),
            'account' => $accountId,
        ]);

        return response()->json($group, 201);
    }

    public function destroyGroup($id)
    {
        $group = CustomerGroup::findOrFail($id);
        $group->delete();

        return response()->json(['message' => 'Group deleted successfully']);
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
            'groups' => 'nullable|string|max:255',
        ]);

        // Determine which account to use: admin can select, others use session account name
        $selectedAccount = $request->input('account', getCurrentShopId());

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
        $customer->groups = $request->input('groups', '');
        $customer->due = $request->input('due', 0);
        // Save the customer to the database
        if ($customer->save()) {

              $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $request->input('name').' (Customer) Added Successfully By '.Auth::user()->name;
            $create->save();

            return redirect()->back()->with('success', 'Customer added successfully!');
        } else {
             $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $request->input('name').' (Customer) Failed to be added By '.Auth::user()->name;
            $create->save();

            return redirect()->back()->with('error', 'Failed to add customer.');
        }
    }

 public function searchCustomer(Request $request)
{
    $query = $request->query('query', '');
    $accountId = $request->query('account', ''); // Get the account parameter
    $user = Auth::user();
    
    if (strlen($query) < 1) {
        return response()->json([]);
    }

    // If account parameter is provided, use it directly (from modal selection)
    if ($accountId) {
        // For non-admin users, verify they have access to the selected shop
        if (strtolower(trim($user->levelStatus)) !== 'admin') {
            $assignedAccountIds = $user->accounts()->pluck('account')->toArray();
            // Fallback to the account field in users table if no pivot records
            if (empty($assignedAccountIds)) {
                $assignedAccountIds = [$user->account];
            }
            if (!in_array($accountId, $assignedAccountIds)) {
                // User doesn't have access to this shop, return empty results
                return response()->json([]);
            }
        }
        
        $customers = customerModel::where('account', $accountId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'phone', 'limits', 'account')
            ->limit(15)
            ->get();
            
        return response()->json($customers);
    }
    

        $selectedShopId = getCurrentShopId();

    $customers = customerModel::where('account', $selectedShopId)
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
     $accountIds = array_column(getUserAccounts(), 'id');
     // Get all account IDs assigned to the user
     
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
      $paidSoFar = (float)(DB::table('debts')->where('account', $customer->account)->where('cId', $customer->id)->sum('amount') ?? 0);
      $remainingDebt = $credit - $paidSoFar;
      $available = max(0, $limits - $remainingDebt);

      return response()->json([
          'id' => $customer->id,
          'name' => $customer->name,
          'phone' => $customer->phone,
          'limits' => $limits,
          'credit' => $credit,
          'paid' => $paidSoFar,
          'remaining_debt' => $remainingDebt,
          'available' => $available
      ]);
 }
    public function editCustomer(Request $req) {
        $req->validate([
            'customerId' => 'required|integer',
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
            'credit' => 'nullable|numeric',
            'due' => 'nullable|integer|min:0|max:30',
            'address' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'groups' => 'nullable|string|max:255',
            'account' => 'nullable|integer|exists:accounts,id',
            'allocation' => 'nullable|integer|exists:users,id',
            'description' => 'nullable|string|max:500',
        ]);

        $get = customerModel::where('id', $req->input('customerId'))->first();

        if (!$get) {
            $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = 'Customer Edit Failed - Customer not found By '.Auth::user()->name;
            $create->save();

            return redirect()->back()->with('error', 'Customer not found or you do not have permission to edit this customer.');
        }

        // Update customer properties
        $get->name = $req->input('name');
        $get->customerId = Str::uuid(); 
        $get->phone = $req->input('contact');
        $get->address = $req->input('address');
        $get->limits = $req->input('credit', 0);
        $get->business = $req->input('type', '');
        $get->description = $req->input('description', 'No description');
        $get->account = $req->input('account');
        $get->employeeId = $req->input('allocation');
        $get->due = $req->input('due', 0);
        $get->groups = $req->input('groups', '');

        // Save the customer to the database
        if ($get->save()) {

              $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $req->input('name').' (Customer) Edited Successfully By '.Auth::user()->name;
            $create->save();

            return redirect()->back()->with('success', 'Customer Edited successfully!')->with(compact('get'));
        } else {
             $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $req->input('name').' (Customer) Failed to be Edit By '.Auth::user()->name;
            $create->save();

            return redirect()->back()->with('error', 'Failed to Edit customer.');
        }

    }
    public function dltCustomer(Request $req) {

        $prodId = $req->input('name');

        $dlts = customerModel::where('account', getCurrentShopId())->where('name', $prodId)->first();
        $dlt = customerModel::where('account', getCurrentShopId())->where('name', $prodId)->delete();
        if ($dlt) {
            $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $prodId.' Customer deleted By '.Auth::user()->name;
            $create->save();

            return redirect()->back()->with('success', 'Customer deleted successfully!');
        } else {
             $create = new logModal();
            $create->title = 'Customer Log';
            $create->description = $prodId.' Customer not deleted By '.Auth::user()->name;
            $create->save();

            return redirect()->back()->with('error', 'Failed to delete customer.');
        }

    }

  public function details($id)
{
    $accountId = getCurrentShopId();
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

    public function bulkAssignGroup(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'integer|exists:customers,id',
            'group' => 'required|string|max:255',
        ]);

        $count = customerModel::whereIn('id', $request->input('customer_ids'))
            ->update(['groups' => $request->input('group')]);

        $name = $request->input('group');

        $create = new logModal();
        $create->title = 'Customer Log';
        $create->description = $count . ' customer(s) assigned to group "' . $name . '" By ' . Auth::user()->name;
        $create->save();

        return redirect()->back()->with('success', $count . ' customer(s) assigned to group "' . $name . '" successfully.');
    }

  public function customerView(Request $req) {
    $user = auth()->user();
    $get = customerModel::where('id', $req->input('id'))->first();

    if (!$get) {
        return redirect()->back()->with('error', 'Customer not found.');
    }

    $accountId = $get->account;
    $accounts = getUserAccounts();
    $selectedAccount = getCurrentShopId();
    $users = usersModel::all();

    $selectedDate = $req->input('selectedDate');
    $baseQuery = salsModel::where('cPhone', $get->id)
        ->orderBy('created_at', 'desc');

    if ($selectedDate) {
        $baseQuery->whereDate('created_at', $selectedDate);
    }

    $paginator = $baseQuery->select('salesName','sales_id', DB::raw('MAX(created_at) as last_date'))
        ->groupBy('salesName', 'sales_id')
        ->orderByDesc('last_date')
        ->paginate(20)
        ->appends($req->except('page'));

    $groupedSales = [];
    foreach ($paginator->items() as $item) {
        $items = (clone $baseQuery)->where('salesName', $item->salesName)->get();
        $groupedSales[] = [
            'salesName' => $item->salesName,
            'sales_id' => $item->sales_id,
            'last_date' => $item->last_date,
            'items' => $items,
            'total_qty' => $items->sum('pQuantity'),
            'total_amount' => $items->sum('totalPrice'),
            'item_count' => $items->count(),
        ];
    }

    $data = compact(
        'groupedSales', 'get', 'selectedDate', 'accounts', 'users', 'selectedAccount', 'paginator'
    );

    return view('customerView', $data);
}

}
