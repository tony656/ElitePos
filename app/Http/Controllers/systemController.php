<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\systemModel;
use Ramsey\Uuid\Uuid;
use App\Models\accountModel;
use App\Models\logModal;
use App\Models\ActiveSession;
use App\Models\usersModel;
use App\Models\productsModel;
use App\Models\stock;
use Illuminate\Support\Facades\Auth;

class systemController extends Controller
{
    public function index() {
        $user = Auth::user();

        $getData = systemModel::first();
        $fetch = accountModel::all();

        // Fetch products from the current account
        $products = productsModel::where('account', 'Loliondo SHop')->get();

        $data = compact(
        'getData','fetch','products'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.settings', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.settings', $data);
    }

    }

      public function getAllAccounts() {
        $getAllAccounts = accountModel::all();

        return response()->json($getAllAccounts);

    }
    public function businessDetails(Request $req) {
    $bName = $req->input('bName') ?? '';
    $address = $req->input('address') ?? '';
    $businessProfilePicture = $req->input('business_profile_picture_path') ?? '';

    // Handle multiple payment services
    $paymentServices = [];
    if ($req->has('payment_services')) {
        foreach ($req->input('payment_services') as $service) {
            if (!empty($service['provider']) && !empty($service['number'])) {
                $paymentServices[] = [
                    'provider' => $service['provider'],
                    'number' => $service['number']
                ];
            }
        }
    }

    // Handle multiple bank accounts
    $bankAccounts = [];
    if ($req->has('bank_accounts')) {
        foreach ($req->input('bank_accounts') as $bank) {
            if (!empty($bank['name']) && !empty($bank['account'])) {
                $bankAccounts[] = [
                    'name' => $bank['name'],
                    'account' => $bank['account']
                ];
            }
        }
    }

    $update = systemModel::first();
    if (!$update) {
        $update = new systemModel();
    }
    $update->bName = $bName;
    $update->address = $address;
    $update->payment_services = !empty($paymentServices) ? json_encode($paymentServices) : null;
    $update->bank_accounts = !empty($bankAccounts) ? json_encode($bankAccounts) : null;

    if (!empty($businessProfilePicture)) {
        $update->business_profile_picture = $businessProfilePicture;
    }

    $update->save();

    if($update) {
        $create = new logModal();
        $create->title = 'Business Information';
        $create->description = 'Business Information Updated By '.session('username');
        $create->save();
        return redirect()->back()->with('success', 'Information Updated Successfully');
    } else {
        $create = new logModal();
        $create->title = 'Business Information';
        $create->description = 'Business Information Failed to Update By '.session('username');
        $create->save();
        return redirect()->back()->with('error', 'Failed to update information');
    }
}

public function personalData(Request $req) {
    $user = Auth::user();
    
    $ownerName = $req->input('ownerName') ?? '';
    $phone = $req->input('phone') ?? '';
    $email = $req->input('email') ?? '';
    $personalProfilePicture = $req->input('personal_profile_picture_path') ?? '';
    
    $update = usersModel::where('id', $user->id)->first();
    $update->name = $ownerName;
    $update->contact = $phone;
    $update->email = $email;
    
    if (!empty($personalProfilePicture)) {
        $update->userImg = $personalProfilePicture;
    }
    
    $update->update();

    if($update) {
        $create = new logModal();
        $create->title = 'Personal Information';
        $create->description = 'Personal Information Updated By '.session('username');
        $create->save();
        return redirect()->back()->with('success', 'Personal Information Updated Successfully');
    } else {
        $create = new logModal();
        $create->title = 'Personal Information';
        $create->description = 'Personal Information Failed to Update By '.session('username');
        $create->save();
        return redirect()->back()->with('error', 'Failed to update personal information');
    }
}

public function uploadProfilePicture(Request $req) {
    $req->validate([
        'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($req->hasFile('profile_picture')) {
        $path = $req->file('profile_picture')->store('profile_pictures', 'public');
        
        return response()->json([
            'success' => true,
            'path' => $path
        ]);
    }

    return response()->json(['success' => false]);
}

    public function newAccount(Request $req) {
        $name = $req->input('name');
        $location = $req->input('location');
        $products = $req->input('products', []); // Get selected products array

        $inst = new accountModel();
        $inst->name = $name;
        $inst->location = $location;
        
        // Store selected products as JSON array
        if (!empty($products)) {
            $inst->products = json_encode($products);
        }
        
        $inst->save();

        if($inst) {
            $create = new logModal();
            $create->title = 'Account Log';
            $create->description = 'Account Created successfully By '.session('username');
            $create->save();
            return redirect()->back()->with('success', 'Account Added');
        } else {
            $create = new logModal();
            $create->title = 'Account Log';
            $create->description = 'Account Created Failed By '.session('username');
            $create->save();
            return redirect()->back()->with('error', 'Account Creation Failed');
        }
    }

    // Fixed method name - was "getAccountProducts" in routes but "getAccountProducts" in controller
    public function getAccountProducts($accountId) {
        // Check if user is authenticated
        if (!session('account')) {
            return response()->json(['error' => 'Unauthorized', 'redirect' => '/login'], 401);
        }

        $account = accountModel::find($accountId);

        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        // Get all products from current account for selection
        $allProducts = productsModel::where('account', session('account'))->get();

        // Get currently assigned product IDs for this account
        $assignedProductIds = json_decode($account->products, true) ?? [];

        return response()->json([
            'account' => $account,
            'products' => $allProducts,
            'assignedProductIds' => $assignedProductIds
        ]);
    }

    // New method to update account products
    public function updateAccountProducts(Request $req) {
        $accountId = $req->input('account_id');
        $products = $req->input('products', []);
        
        $account = accountModel::find($accountId);
        
        if (!$account) {
            return redirect()->back()->with('error', 'Account not found');
        }
        
        $account->products = json_encode($products);
        $account->update();

        if($account) {
            $create = new logModal();
            $create->title = 'Account Products';
            $create->description = 'Account products updated for ' . $account->name . ' By '.session('username');
            $create->save();
            return redirect()->back()->with('success', 'Account products updated successfully');
        } else {
            $create = new logModal();
            $create->title = 'Account Products';
            $create->description = 'Failed to update account products for ' . $account->name . ' By '.session('username');
            $create->save();
            return redirect()->back()->with('error', 'Failed to update account products');
        }
    }

    // New method to fetch all products (for AJAX)
    public function getAllProducts() {
        $products = productsModel::where('account', session('account'))->get();
        return response()->json($products);
    }

    public function deleteAccount(Request $req) {
        $id = $req->input('accountId'); // Fixed: was 'acountId'

        $dlt = accountModel::where('id', $id)->delete();

        if($dlt) {
            $create = new logModal();
            $create->title = 'Account Log';
            $create->description = 'Account Deleted successfully By '.session('username');
            $create->save();
            return redirect()->back()->with('success', 'Account Deleted');
        } else {
            $create = new logModal();
            $create->title = 'Account Log';
            $create->description = 'Account Delete Failed By '.session('username');
            $create->save();
            return redirect()->back()->with('error', 'Account Delete Failed');
        }
    }

    public function security() {
        $user = Auth::user();

        $getOnline = usersModel::where('status', 'Online')->count();
        $getOffline = usersModel::where('status', '!=', 'Online')->count();
        $logs = logModal::orderBy('id', 'desc')->get();

         $data = compact(
        'getOnline','getOffline', 'logs'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.security', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.security', $data);
    }

    }

    public function getActiveSessions() {
        $sessions = ActiveSession::all();
        return response()->json($sessions);
    }

    public function removeUser(Request $request, $sessionId) {
        $session = ActiveSession::find($sessionId);
        if ($session) {
            $session->delete();
            $create = new logModal();
            $create->title = 'User Removed';
            $create->description = 'User session removed by ' . session('username');
            $create->user = session('username');
            $create->status = 'done';
            $create->save();
            return response()->json(['message' => 'User removed successfully']);
        }
        return response()->json(['message' => 'Session not found'], 404);
    }

    public function suspendUser(Request $request, $userId) {
        $user = usersModel::find($userId);
        if ($user) {
            $user->status = 'Suspended';
            $user->save();
            $create = new logModal();
            $create->title = 'User Suspended';
            $create->description = 'User suspended by ' . session('username');
            $create->user = session('username');
            $create->status = 'done';
            $create->save();
            return response()->json(['message' => 'User suspended successfully']);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    public function authorizeSession(Request $request, $sessionId) {
        $session = ActiveSession::find($sessionId);
        if ($session) {
            $session->status = 'Authorized';
            $session->save();
            return response()->json(['message' => 'Session authorized']);
        }
        return response()->json(['message' => 'Session not found'], 404);
    }

    public function blockAllAccess(Request $request) {
        ActiveSession::where('status', '!=', 'Blocked')->update(['status' => 'Blocked']);
        $create = new logModal();
        $create->title = 'All Access Blocked';
        $create->description = 'All access blocked by ' . session('username');
        $create->user = session('username');
        $create->status = 'done';
        $create->save();
        return response()->json(['message' => 'All access blocked']);
    }

    public function getSecurityAlerts() {
        $alerts = logModal::where('status', 'suspicious')->get();
        return response()->json($alerts);
    }
    
    // Add this method if you have a switch account functionality
    public function switchAccount(Request $req) {
        $account = $req->input('account');
        session(['account' => $account]);
        return redirect()->back()->with('success', 'Account switched to ' . $account);
    }
}