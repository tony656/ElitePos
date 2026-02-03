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

class customerController extends Controller
{

    
    public function index()
    {
        $Account = session('account');
        $user = Auth()->user();

        $fetch = customerModel::where('account', $Account)->get(); // Fetch all customers from the database
        $myCustomers = customerModel::where('account', $Account)->where('employeeId', $user->id)->get();

        $users = usersModel::where('account', $Account)->get();

        $data = compact(
        'fetch','myCustomers','users'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.customers', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.customer', $data);
    }
}
    public function addCustomer(Request $request)
    {
        $user = auth()->user();
        $Account = session('account');

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
            'credit' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'allocation' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $look = customerModel::where('name', $request->input('name')
                             )->where('account', $Account)->get();

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
        $customer->account = $Account;

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


    public function editCustomer(Request $req) {
        $req->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
            'credit' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $get = customerModel::where('id', $req->input('customerId')
                             )->where('account', session('account'))->first();

        /*if($look > 1) {
            return redirect()->back()->with('success', 'Customer with this name is available');
        }*/
        // Create a new customer instance
        $get->name = $req->input('name');
        $get->customerId = Str::uuid(); 
        $get->phone = $req->input('contact');
        $get->address = $req->input('address');
        $get->limits = $req->input('credit', 0);
        $get->business = $req->input('type', '');
        $get->description = $req->input('description', 'No description');
        $get->account = session('account');

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

        $Account = session('account');

        $prodId = $req->input('name');

        $dlts = customerModel::where('account', $Account)->where('name', $prodId)->first();
        $dlt = customerModel::where('account', $Account)->where('name', $prodId)->delete();
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
    $customers = salsModel::where('account', session('account'))->where('cName', $id)->get();

    if ($customers->isEmpty()) {
        return response()->json(['error' => 'Customer not found'], 404);
    }

    $results = [];

    foreach ($customers as $customer) {
        $product = productsModel::where('account', session('account'))->where('product_id', $customer->productId)->first();

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

            $sales = salsModel::where('account', session('account'))->where('cName', $req->input('name'))->get();

              
     $data = compact(
        'sales','get'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.customerView', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.customerView', $data);
    }
}

}
