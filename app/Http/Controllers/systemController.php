<?php

namespace App\Http\Controllers;

use App\Models\adsModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\systemModel;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\accountModel;
use App\Models\logModal;
use App\Models\ActiveSession;
use App\Models\usersModel;
use App\Models\productsModel;
use App\Models\stock;
use Illuminate\Support\Facades\Auth;
use App\Models\customerModel;
use App\Models\ordersModel;
use App\Models\debtsModel;
use App\Models\UserAccount;
use App\Models\BankingChip;
use Carbon\Carbon;
use App\Models\salsModel;
use function getSessionAccountId;

class systemController extends Controller
{
    private function ensureUserPermission(string $permission, string $deniedMessage)
    {
        $user = Auth::user();

        // Allow if user is Admin OR has valid emergency access
        $isAdmin = $user && strtolower(trim($user->levelStatus)) === 'admin';
        $hasEmergencyAccess = session('emergency_access') &&
                              session('emergency_expires_at') &&
                              now()->lessThan(\Carbon\Carbon::parse(session('emergency_expires_at')));
        
        if ($isAdmin || $hasEmergencyAccess) {
            return null;
        }

        if (!canUser($permission)) {
            return redirect()->back()->with('error', $deniedMessage);
        }

        return null;
    }
    public function index() {
        $user = Auth::user();

        $getData = systemModel::first();
        
        // For Admin: show all shops
        // For regular users: show only assigned shops
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $fetch = accountModel::orderBy('name', 'asc')->get();
        } else {
            // Get the accounts this user is assigned to via UserAccount pivot
            $assignedAccounts = UserAccount::where('user_id', $user->id)
                ->pluck('account')
                ->toArray();
            
            if (empty($assignedAccounts)) {
                $fetch = collect(); // No assigned shops
            } else {
                // Get shops where account name matches the assigned accounts
                $fetch = accountModel::whereIn('name', $assignedAccounts)
                    ->orderBy('name', 'asc')
                    ->get();
            }
        }

        foreach($fetch as $shops) {
            $shops->users = usersModel::where('account', $shops->id)->count();
            $shops->products = productsModel::where('account', $shops->id)->count();
            $shops->customers = customerModel::where('account', $shops->id)->count();
        }
        
        // Get current shop details for the active session account
        $currentShop = accountModel::where('name', getSessionAccountDisplayName())->first();

        $data = compact(
            'getData', 'fetch', 'currentShop'
        );

 if (strtolower(trim($user->levelStatus)) === 'admin') {
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
        if (!getSessionAccountId()) {
            return response()->json(['error' => 'Unauthorized', 'redirect' => '/login'], 401);
        }

        $account = accountModel::find($accountId);

        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        // Get all products from current account for selection
        $allProducts = productsModel::where('account', getSessionAccountId())->get();

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
        $products = productsModel::where('account', getSessionAccountId())->get();
        return response()->json(['products' => $products]);
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

    public function updateAccount(Request $req) {
        $id = $req->input('accountId');
        $name = $req->input('name');
        $location = $req->input('location');

        $update = accountModel::where('id', $id)->first();
        
        if (!$update) {
            return redirect()->back()->with('error', 'Account not found');
        }

        $update->name = $name;
        $update->location = $location;
        $update->update();

        if($update) {
            $create = new logModal();
            $create->title = 'Account Log';
            $create->description = 'Account Updated successfully By '.session('username');
            $create->save();
            return redirect()->back()->with('success', 'Account Updated');
        } else {
            $create = new logModal();
            $create->title = 'Account Log';
            $create->description = 'Account Update Failed By '.session('username');
            $create->save();
            return redirect()->back()->with('error', 'Account Update Failed');
        }
    }

    // New method for admin to view all shops invoices
    public function allInvoices(Request $req) {
        $user = Auth::user();

        if ($permissionCheck = $this->ensureUserPermission('view_all_shops', 'You do not have permission to view all shop invoices.')) {
            return $permissionCheck;
        }
        
        // Get all shops
        $shops = accountModel::orderBy('name', 'asc')->get();
        
        // Get selected shop or default to first
        $selectedShop = $req->input('shop') ?? ($shops->first()->name ?? getSessionAccountDisplayName());
        
        // Get invoices for selected shop
        $invoices = ordersModel::where('account', $selectedShop)
            ->where('orderName', '!=', '')
            ->whereIn('status', ['Paid', 'Debt', 'Partial'])
            ->selectRaw('
                orderName,
                MAX(cName) as cName,
                MAX(cPhone) as cPhone,
                MAX(status) as status,
                SUM(totalPrice) as totalPrice,
                MAX(created_at) as created_at,
                MAX(served_by) as served_by
            ')
            ->groupBy('orderName')
            ->orderByDesc('created_at')
            ->get();
        
        // Get shop details
        $shopDetails = accountModel::where('id', $selectedShop)->first();
        
        $data = compact('shops', 'selectedShop', 'invoices', 'shopDetails');
        
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.allInvoices', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.allInvoices', $data);
        }
    }

    public function security() {
        $user = Auth::user();

        $getOnline = usersModel::where('status', 'Online')->count();
        $getOffline = usersModel::where('status', '!=', 'Online')->count();
        
        // Get system security settings
        $systemSettings = systemModel::first();
        $blockSignins = $systemSettings ? $systemSettings->block_signins : false;
        $systemShutdown = $systemSettings ? $systemSettings->system_shutdown : false;
        $faceRecognitionEnabled = $systemSettings ? (bool)$systemSettings->face_recognition_enabled : false;

        $data = compact(
            'getOnline', 'getOffline', 'blockSignins', 'systemShutdown', 'faceRecognitionEnabled'
        );

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.security', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.security', $data);
        }

    }
    /**
     * Get system status API (for frontend)
     */
    public function getSystemStatus()
    {
        $system = systemModel::first();
        
        return response()->json([
            'face_recognition_enabled' => $system ? (bool)$system->face_recognition_enabled : false,
            'face_verification_timeout' => $system ? $system->face_verification_timeout : 5,
            'system_shutdown' => $system ? (bool)$system->system_shutdown : false,
            'block_signins' => $system ? (bool)$system->block_signins : false,
        ]);
    }
    public function toggleBlockSignins(Request $request) {
        $user = Auth::user();
        
        \Log::info('toggleBlockSignins called', [
            'user' => $user ? $user->name : 'null',
            'levelStatus' => $user ? $user->levelStatus : 'null',
            'emergency_access' => session('emergency_access'),
            'emergency_expires_at' => session('emergency_expires_at'),
            'block_value' => $request->has('block') ? 1 : 0,
            'request_method' => $request->method(),
        ]);
        
        // Only admin can toggle this, but allow if user has valid emergency access
        $isAdmin = strtolower(trim($user->levelStatus)) === 'admin';
        $hasEmergencyAccess = session('emergency_access') &&
                              session('emergency_expires_at') &&
                              now()->lessThan(\Carbon\Carbon::parse(session('emergency_expires_at')));
        
        \Log::info('Permission check', [
            'isAdmin' => $isAdmin,
            'hasEmergencyAccess' => $hasEmergencyAccess,
            'allowed' => ($isAdmin || $hasEmergencyAccess)
        ]);
        
        if (!$isAdmin && !$hasEmergencyAccess) {
            return response()->json(['message' => 'Unauthorized - Admin or Emergency access required'], 403);
        }
        
        $system = systemModel::first();
        if (!$system) {
            $system = new systemModel();
        }
        
        $system->block_signins = $request->boolean('block') ? 1 : 0;
        $system->save();
        
        \Log::info('Block signins updated', [
            'new_value' => $system->block_signins,
            'username' => session('username')
        ]);
        
        $action = $system->block_signins ? 'BLOCKED' : 'UNBLOCKED';
        logModal::create([
            'title' => 'Sign-in Control',
            'description' => 'All user sign-ins have been ' . $action . ' by ' . (session('username') ?? 'Unknown User'),
            'user' => session('username') ?? 'Unknown',
            'status' => 'done'
        ]);
        
        return response()->json([
            'message' => 'Sign-in block status updated successfully',
            'blocked' => (bool)$system->block_signins
        ]);
    }
    
    public function toggleSystemShutdown(Request $request) {
        $user = Auth::user();
        
        // Only admin can toggle this, but allow if user has valid emergency access
        $isAdmin = strtolower(trim($user->levelStatus)) === 'admin';
        $hasEmergencyAccess = session('emergency_access') &&
                              session('emergency_expires_at') &&
                              now()->lessThan(\Carbon\Carbon::parse(session('emergency_expires_at')));
        
        if (!$isAdmin && !$hasEmergencyAccess) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $system = systemModel::first();
        if (!$system) {
            $system = new systemModel();
        }
        
        $system->system_shutdown = $request->boolean('shutdown') ? 1 : 0;
        $system->save();
        
        $action = $system->system_shutdown ? 'SHUTDOWN' : 'RESTORED';
        logModal::create([
            'title' => 'System Control',
            'description' => 'System has been ' . $action . ' by ' . session('username'),
            'user' => session('username'),
            'status' => 'done'
        ]);
        
        return response()->json([
            'message' => 'System shutdown status updated',
            'shutdown' => (bool)$system->system_shutdown
        ]);
    }

    public function toggleFaceRecognition(Request $request)
    {
        $user = Auth::user();
        
        // Only admin can toggle this, but allow if user has valid emergency access
        $isAdmin = strtolower(trim($user->levelStatus)) === 'admin';
        $hasEmergencyAccess = session('emergency_access') &&
                              session('emergency_expires_at') &&
                              now()->lessThan(\Carbon\Carbon::parse(session('emergency_expires_at')));
        
        if (!$isAdmin && !$hasEmergencyAccess) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $system = systemModel::first();
        if (!$system) {
            $system = new systemModel();
        }
        
        $system->face_recognition_enabled = $request->boolean('enabled') ? 1 : 0;
        $system->save();
        
        $action = $system->face_recognition_enabled ? 'ENABLED' : 'DISABLED';
        logModal::create([
            'title' => 'Face Recognition Control',
            'description' => 'Face recognition has been ' . $action . ' by ' . session('username'),
            'user' => session('username'),
            'status' => 'done'
        ]);
        
        return response()->json([
            'message' => 'Face recognition status updated',
            'enabled' => (bool)$system->face_recognition_enabled
        ]);
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
    
    // Switch account with permission validation
    public function switchAccount(Request $req) {
        $user = Auth::user();
        $accountId = $req->input('account');
        
        // Verify user has access to this account (check by name)
        $hasAccess = UserAccount::where('user_id', $user->id)
            ->where('account', $accountId)
            ->exists();
        
        if (!$hasAccess && $user->levelStatus !== 'Admin') {
            return redirect()->back()->with('error', 'You do not have permission to access this shop.');
        }
        
        // Get account ID for the selected account name
        $account = accountModel::where('id', $accountId)->first();
        $accountId = $account ? $account->id : null;
        
        // Store both account name (for display) and account_id (for queries) in session
        session([
            'account' => $account->name,
            'account_id' => $accountId,
        ]);
        
        return redirect()->back()->with('success', 'Account switched to ' . $account->name);
    }

    public function ads(Request $req) {
        $user = Auth::user();

        if ($req->isMethod('post')) {
            return $this->store($req);
        }

        \Log::info('Ads method called', [
            'user_id' => $user->id ?? null,
            'user_level' => $user->levelStatus ?? null,
            'request_method' => $req->method()
        ]);

        $ads = adsModel::orderBy('created_at', 'desc')
                ->paginate(5);

        \Log::info('Ads query result', [
            'ads_count' => $ads->count(),
            'total_ads' => $ads->total(),
            'ads_data' => $ads->items()
        ]);

                $data = compact(
                    'ads'
                );

                if (strtolower(trim($user->levelStatus)) === 'admin') {
        return view('admin.ads', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.ads', $data);
    }

    }
    
    // Store new ad
    private function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);
        
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
        $photo = $request->file('image');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photo->move(public_path('images'), $photoName);
                
                // Create ad record
                 adsModel::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'image_path' => $photoName,
                    'status' => 'active'
                ]);
                
                return redirect()->back()
                    ->with('success', 'Advertisement uploaded successfully!');
            }
            
            return back()->with('error', 'Image upload failed.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to upload ad: ' . $e->getMessage());
        }
    }
    
    // API endpoint for AJAX requests (optional)
    public function apiIndex()
    {
        $ads = adsModel::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($ad) {
                    return [
                        'id' => $ad->id,
                        'title' => $ad->title,
                        'description' => $ad->description,
                        'image_url' => asset('images/' . $ad->image_path),
                        'created_at' => $ad->created_at->format('M d, Y'),
                    ];
                });
        
        return response()->json($ads);
    }
    
    // API endpoint for storing ads via AJAX (optional)
    public function apiStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        
        try {
            $imagePath = $request->file('image')->store('ads', 'public');
            
            $ad = adsModel::create([
                'title' => $request->title,
                'description' => $request->description,
                'image_path' => $imagePath,
                'status' => 'active'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Ad uploaded successfully',
                'ad' => [
                    'id' => $ad->id,
                    'title' => $ad->title,
                    'description' => $ad->description,
                    'image_url' => asset('storage/' . $ad->image_path),
                    'created_at' => $ad->created_at->format('M d, Y'),
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload ad: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Delete ad
    public function destroyAd(Request $request)
    {
        try {
            $adId = $request->input('ad_id');
            $ad = adsModel::findOrFail($adId);
            
            // Delete image from public/images folder
            $imagePath = public_path('images/' . $ad->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            $ad->delete();
            
            return redirect()->route('admin.ads')
                ->with('success', 'Advertisement deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.ads')
                ->with('error', 'Failed to delete ad.');
        }
    }
    
    // New method: Show shops with invoices (grouped by shop)
    public function shopInvoices(Request $request)
    {
        $user = Auth::user();

        if ($permissionCheck = $this->ensureUserPermission('view_shop_debts', 'You do not have permission to view shop debts.')) {
            return $permissionCheck;
        }
        
        // Get date filter parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Get all shops with their invoice counts and total amounts
        $shops = accountModel::orderBy('name', 'asc')->get();
        
        $shopsWithInvoices = [];
        
        foreach ($shops as $shop) {
            // Build query for invoices
            $invoiceQuery = ordersModel::where('account', $shop->id)
                ->where('orderName', '!=', '')
                ->whereIn('status', ['Debt', 'Partial', 'Paid']);
            
            // Apply date filter if provided
            if (!empty($startDate)) {
                $invoiceQuery->whereDate('created_at', '>=', Carbon::parse($startDate)->toDateString());
            }
            if (!empty($endDate)) {
                $invoiceQuery->whereDate('created_at', '<=', Carbon::parse($endDate)->toDateString());
            }
            
            $invoices = $invoiceQuery
                ->selectRaw('orderName, MAX(created_at) as last_activity')
                ->groupBy('orderName')
                ->orderByDesc('last_activity')
                ->get();
            
            $invoiceCount = $invoices->count();
            
            // Calculate total remaining debt (for Debt and Partial only) with date filter
            $debtQuery = ordersModel::where('account', $shop->id)
                ->where('orderName', '!=', '')
                ->whereIn('status', ['Debt', 'Partial']);
            
            if (!empty($startDate)) {
                $debtQuery->whereDate('created_at', '>=', Carbon::parse($startDate)->toDateString());
            }
            if (!empty($endDate)) {
                $debtQuery->whereDate('created_at', '<=', Carbon::parse($endDate)->toDateString());
            }
            
            $totalDebt = $debtQuery->sum('totalPrice');
            
            // Count actual debt records (Debt + Partial) with date filter
            $debtCountQuery = ordersModel::where('account', $shop->id)
                ->where('orderName', '!=', '')
                ->whereIn('status', ['Debt', 'Partial']);
            
            if (!empty($startDate)) {
                $debtCountQuery->whereDate('created_at', '>=', Carbon::parse($startDate)->toDateString());
            }
            if (!empty($endDate)) {
                $debtCountQuery->whereDate('created_at', '<=', Carbon::parse($endDate)->toDateString());
            }
            
            $debtCount = $debtCountQuery->distinct('orderName')->count('orderName');
            
            // Get last activity date
            $lastActivity = $invoices->max('last_activity');
            
            // Only include shops that have invoices (including fully paid)
            if ($invoiceCount > 0) {
                $shopsWithInvoices[] = [
                    'id' => $shop->id,
                    'name' => $shop->name,
                    'location' => $shop->location,
                    'invoice_count' => $invoiceCount,
                    'total_amount' => $totalDebt,
                    'debt_count' => $debtCount,
                    'last_activity' => $lastActivity
                ];
            }
        }
        
        // Sort by last_activity (most recent first)
        usort($shopsWithInvoices, function($a, $b) {
            return strtotime($b['last_activity']) - strtotime($a['last_activity']);
        });
        
        $data = compact('shopsWithInvoices', 'startDate', 'endDate', 'shops');
        
        // Convert to collection for view methods
        $data['shopsWithInvoices'] = collect($shopsWithInvoices);
        
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.shopInvoices', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.shopInvoices', $data);
        }
    }
    
    // New method: Show customers with debts for a specific shop
    public function shopDebtors($shopName)
    {
        $user = Auth::user();

        if ($permissionCheck = $this->ensureUserPermission('view_shop_debts', 'You do not have permission to view shop debtors.')) {
            return $permissionCheck;
        }
        
        // Get shop details by ID
        $shop = accountModel::where('id', $shopName)->first();
        
        if (!$shop) {
            return redirect()->back()->with('error', 'Shop not found');
        }
        
        // Get all customers with invoices (Debt, Partial, and Paid for full picture)
        // First, get all unique customer names with any invoice status
        $customerNames = ordersModel::where('account', $shop->id)
            ->whereIn('status', ['Debt', 'Partial', 'Paid'])
            ->where('cName', '!=', '')
            ->distinct('cName')
            ->pluck('cName');
        
        // Get customer details from customers table
        $customers = customerModel::where('account', $shop->id)
            ->whereIn('name', $customerNames)
            ->get()
            ->keyBy('name');
        
        // Get all invoice data grouped by customer with payment info
        $invoiceData = ordersModel::where('account', $shop->id)
            ->whereIn('status', ['Debt', 'Partial', 'Paid'])
            ->where('cName', '!=', '')
            ->where('orderName', '!=', '')
            ->selectRaw('
                cName,
                orderName,
                SUM(totalPrice) as total_debt,
                MAX(status) as status,
                MAX(created_at) as last_order_date
            ')
            ->groupBy('cName', 'orderName')
            ->get()
            ->groupBy('cName');
        
        // Build debtors array with payment info
        $debtors = collect();
        foreach ($customerNames as $customerName) {
            $customer = $customers->get($customerName);
            $customerInvoices = $invoiceData->get($customerName, collect());
            
            $totalDebt = 0;
            $totalPaid = 0;
            $invoiceCount = 0;
            $paidInvoiceCount = 0;
            $lastActivity = null;
            
            foreach ($customerInvoices as $invoice) {
                $invoiceTotal = $invoice->total_debt ?? 0;
                $totalDebt += $invoiceTotal;
                $invoiceCount++;
                
                // Get amount already paid for this invoice
                $firstOrder = ordersModel::where('account', $shop->id)
                    ->where('orderName', $invoice->orderName)
                    ->first();
                
                if ($firstOrder) {
                    $paidAmount = debtsModel::where('orderId', $firstOrder->order_id)
                        ->where('account', $shop->id)
                        ->sum('amount');
                    $totalPaid += $paidAmount;
                    
                    if ($paidAmount >= $invoiceTotal) {
                        $paidInvoiceCount++;
                    }
                }
                
                if (!$lastActivity || strtotime($invoice->last_order_date) > strtotime($lastActivity)) {
                    $lastActivity = $invoice->last_order_date;
                }
            }
            
            $remaining = max(0, $totalDebt - $totalPaid);
            
            $debtors->push((object)[
                'cName' => $customerName,
                'cPhone' => $customer->contact ?? 'N/A',
                'total_debt' => $totalDebt,
                'total_paid' => $totalPaid,
                'remaining' => $remaining,
                'order_count' => $invoiceCount,
                'paid_invoices' => $paidInvoiceCount,
                'last_order_date' => $lastActivity,
                'is_fully_paid' => $remaining == 0 && $invoiceCount > 0
            ]);
        }
        
        // Sort by last activity (most recent first)
        $debtors = $debtors->sortByDesc('last_order_date')->values();
        
        $data = compact('shop', 'debtors');
        
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.shopDebtors', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.shopDebtors', $data);
        }
    }
    
    // New method: Show products for a specific customer debt
    public function customerDebtProducts(Request $request)
    {
        $user = Auth::user();

        if ($permissionCheck = $this->ensureUserPermission('view_shop_debts', 'You do not have permission to view customer debt products.')) {
            return $permissionCheck;
        }
        
        $customerName = $request->input('customer');
        $shopName = $request->input('shop');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $reset = $request->input('reset');

        if ($reset) {
            $startDate = $endDate = null;
        }

        // Get all order items for this customer with Debt or Partial status
        // Join with products table to get product name
        $debtProductsQuery = ordersModel::leftJoin('products', function($join) {
                $join->on('orders.productId', '=', 'products.product_id')
                     ->on('orders.account', '=', 'products.account');
            })
            ->where('orders.account', $shopName)
            ->where('orders.cName', $customerName)
            ->whereIn('orders.status', ['Debt', 'Partial'])
            ->where('orders.orderName', '!=', '')
            ->where('orders.productId', '!=', '');

        if (!empty($startDate)) {
            $debtProductsQuery->whereDate('orders.created_at', '>=', Carbon::parse($startDate)->toDateString());
        }

        if (!empty($endDate)) {
            $debtProductsQuery->whereDate('orders.created_at', '<=', Carbon::parse($endDate)->toDateString());
        }

        $debtProducts = $debtProductsQuery
            ->select('orders.*', 'products.name01')
            ->orderByDesc('orders.created_at')
            ->get();
        
        // Get unique invoice numbers for grouping
        $invoices = $debtProducts->groupBy('orderName');
        
        // Calculate paid amounts for each invoice from debtsModel table
        $invoicePayments = [];
        foreach ($invoices as $invoiceName => $items) {
            // Get the first item to get order_id
            $firstItem = $items->first();
            
            // Get total debt - always use sum of totalPrice as the actual invoice total
            // The credit field might be 0 or null for some records
            $totalDebt = $items->sum('totalPrice');
            
            // Get amount already paid from debtsModel table
            // Also check account to ensure we're looking at the right shop's payments
            $paidAmount = debtsModel::where('orderId', $firstItem->order_id)
                ->where('account', $shopName)
                ->sum('amount');
            
            // Calculate remaining (ensure it doesn't go below 0)
            $remaining = max(0, $totalDebt - $paidAmount);
            
            $invoicePayments[$invoiceName] = [
                'paid' => $paidAmount,
                'total' => $totalDebt,
                'remaining' => $remaining
            ];
        }
        
        // Get available chip balance from last chip entry (cumulative total)
        $shop = accountModel::where('id', $shopName)->first();
        $availableChip = 0;
        if ($shop) {
            $lastChip = BankingChip::where('shop_id', $shop->id)
                ->orderBy('id', 'desc')
                ->first();
            if ($lastChip) {
                $availableChip = $lastChip->available_chip;
            }
        }
        
        $data = compact(
            'customerName', 'shopName', 'debtProducts', 'invoices', 'invoicePayments',
            'startDate', 'endDate', 'availableChip'
        );
        
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.customerDebtProducts', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.customerDebtProducts', $data);
        }
    }
    
    // Undo latest debt payment for a specific invoice
    public function undoInvoiceDebt(Request $request)
    {
        if ($permissionCheck = $this->ensureUserPermission('pay_debts', 'You do not have permission to undo debt payments.')) {
            return $permissionCheck;
        }

        $invoiceName = $request->input('invoiceName');
        $shopName = $request->input('shopName');

        if (!$invoiceName || !$shopName) {
            return redirect()->back()->with('error', 'Invalid invoice details');
        }

        $firstOrder = ordersModel::where('account', $shopName)
            ->where('orderName', $invoiceName)
            ->first();

        if (!$firstOrder) {
            return redirect()->back()->with('error', 'Invoice not found');
        }

        $latestPayment = debtsModel::where('orderId', $firstOrder->order_id)
            ->where('account', $shopName)
            ->orderByDesc('id')
            ->first();

        if (!$latestPayment) {
            return redirect()->back()->with('error', 'No payment found to undo for this invoice');
        }

        $undoneAmount = $latestPayment->amount;
        $latestPayment->delete();

        logModal::create([
            'title' => 'Debt Payment Undo',
            'description' => 'Payment undo of '.$undoneAmount.' for invoice '.$invoiceName.' by '.session('username')
        ]);

        return redirect()->back()->with('success', 'Last payment of ' . number_format($undoneAmount) . ' has been undone.');
    }

    // New method: Pay debt for a specific invoice
    public function payInvoiceDebt(Request $request)
    {
        if ($permissionCheck = $this->ensureUserPermission('pay_debts', 'You do not have permission to pay debts.')) {
            return $permissionCheck;
        }

        $invoiceName = $request->input('invoiceName');
        $shopName = $request->input('shopName');
        $paymentMethod = $request->input('payment_method', 'cash'); // 'cash' or 'chip'
        $chipAmount = $request->input('chip_amount', 0); // Only used if payment_method is 'chip'
        $paymentAmount = (float) $request->input('paymentAmount', 0);
        $paymentDate = $request->input('payment_date');
  
        
        if (!$invoiceName || $paymentAmount < 0) {
            return redirect()->back()->with('error', 'Invalid invoice or amount');
        }
        
        $userName = session('username');
        
        // Get the first order item for this invoice
        $firstOrder = ordersModel::where('account', $shopName)
            ->where('orderName', $invoiceName)
            ->first();
        
        if (!$firstOrder) {
            return redirect()->back()->with('error', 'Invoice not found');
        }
        
        // Get total debt - get from orders table's totalPrice sum for this invoice
        $allOrderItems = ordersModel::where('account', $shopName)
            ->where('orderName', $invoiceName)
            ->get();
        $totalDebt = $allOrderItems->sum('totalPrice');
        
        // Get amount already paid - also check account to match the shop
        $paidAmount = debtsModel::where('orderId', $firstOrder->order_id)
            ->where('account', $shopName)
            ->sum('amount');
        $remainingDebt = max(0, $totalDebt - $paidAmount);
        
        if ($remainingDebt <= 0) {
            return redirect()->back()->with('error', 'This invoice is already fully paid');
        }
        
        if ($paymentAmount > $remainingDebt) {
            $paymentAmount = $remainingDebt; // Cap at remaining amount
        }

        // Handle chip payment validation and deduction
        if ($paymentMethod === 'chip') {
    $chipPayment = (float) $chipAmount;

    if ($chipPayment <= 0) {
        return redirect()->back()->with('error', 'Chip amount is required when payment method is chip');
    }

    // Get shop
    $shop = accountModel::find($shopName);
    if (!$shop) {
        return redirect()->back()->with('error', 'Shop not found');
    }

    // Get latest chip entry (LAST ROW ONLY)
    $chipEntry = BankingChip::where('shop_id', $shop->id)
        ->orderBy('id', 'desc')
        ->first();

    if (!$chipEntry) {
        return redirect()->back()->with('error', 'No chip record found for this shop');
    }

    // Get current available chip from LAST entry
    $availableChip = $chipEntry->available_chip ?? 0;

    if ($availableChip <= 0) {
        return redirect()->back()->with('error', 'No chip balance available');
    }

    if ($chipPayment > $availableChip) {
        return redirect()->back()->with(
            'error',
            'Insufficient chip balance. Available: ' . number_format($availableChip) . ' Tsh'
        );
    }

    // Deduct from chip_amount on the last entry
    // The model's observer will automatically recalculate available_chip for all entries
    $chipEntry->chip_amount -= $chipPayment;
    $chipEntry->save(); // triggers recalculation automatically

    // Total payment
    $TotalPayment = $paymentAmount + $chipPayment;

} else {
    $chipPayment = 0;
    $TotalPayment = $paymentAmount;
}
        // Record payment ONLY in debtsModel table - use shopName for reports to work
        // Fallback to getSessionAccountDisplayName() if shopName is empty
        $accountName = !empty($shopName) ? $shopName : getSessionAccountId();
        
        // Create payment record with custom created_at date if provided
        $payment = new debtsModel();
        $payment->cName   = $firstOrder->cName;
        $payment->debtId  = $firstOrder->id;
        $payment->cId     = $firstOrder->cPhone;
        $payment->orderId = $firstOrder->order_id;
        $payment->amount  = $TotalPayment;
        $payment->account = $accountName;
        $payment->payment_method = $paymentMethod;
        $payment->chip_amount = $chipPayment;
        
        // Set custom created_at if payment_date is provided
        if (!empty($paymentDate)) {
            $payment->created_at = Carbon::parse($paymentDate)->toDateString() . ' 23:59:59';
        }
        
        $payment->save();

        // Log the payment
        $logDescription = 'Payment of ' . number_format($paymentAmount);
        if ($paymentMethod === 'chip' && $chipPayment > 0) {
            $logDescription .= ' (Cash: ' . number_format($TotalPayment - $chipPayment) . ', Chip: ' . number_format($chipPayment) . ')';
        }
        $logDescription .= ' for invoice ' . $invoiceName . ' by ' . $userName;
        
        logModal::create([
            'title' => 'Debt Payment',
            'description' => $logDescription
        ]);
        
        // Recalculate remaining after payment
        $newPaidAmount = $paidAmount + $paymentAmount;
        $newRemaining = $totalDebt - $newPaidAmount;
        
        if ($newRemaining <= 0) {
            return redirect()->back()->with('success', 'Invoice fully paid!');
        } else {
            return redirect()->back()->with('success', 'Payment of ' . number_format($paymentAmount) . ' recorded. Remaining: ' . number_format($newRemaining));
        }
    }
    
    
    // New method: Show all paid debt payments with summary
    public function paidInvoices(Request $request)
    {
        $user = Auth::user();

        if ($permissionCheck = $this->ensureUserPermission('view_shop_debts', 'You do not have permission to view paid invoices.')) {
            return $permissionCheck;
        }
        
        $shopName = $request->input('shop', getSessionAccountDisplayName());
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Get all debt payments for this shop
        $paymentsQuery = debtsModel::where('account', $shopName);
        
        if (!empty($startDate)) {
            $paymentsQuery->whereDate('created_at', '>=', Carbon::parse($startDate)->toDateString());
        }
        
        if (!empty($endDate)) {
            $paymentsQuery->whereDate('created_at', '<=', Carbon::parse($endDate)->toDateString());
        }
        
        $payments = $paymentsQuery->orderByDesc('created_at')->get();
        
        // Get summary statistics
        $totalPaid = $payments->sum('amount');
        $paymentCount = $payments->count();
        $uniqueInvoices = $payments->unique('orderId')->count();
        
        // Get unique customers who made payments
        $customersWithPayments = $payments->unique('cName')->count();
        
        // Group payments by customer for additional insights
        $paymentsByCustomer = $payments->groupBy('cName')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount'),
                'last_payment' => $group->max('created_at')
            ];
        });
        
        // Get shop details
        $shop = accountModel::where('id', $shopName)->first();
        
        // Get all shops for filter (for admin view)
        $shops = accountModel::orderBy('name', 'asc')->get();
        
        $data = compact(
            'payments', 'shopName', 'startDate', 'endDate',
            'totalPaid', 'paymentCount', 'uniqueInvoices', 'customersWithPayments',
            'paymentsByCustomer', 'shop', 'shops'
        );
        
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.paidInvoices', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.paidInvoices', $data);
        }
    }

    /**
     * Delete a paid invoice payment
     */
    public function deletePaidInvoice(Request $request)
    {
        $user = Auth::user();

        if ($permissionCheck = $this->ensureUserPermission('view_shop_debts', 'You do not have permission to delete paid invoices.')) {
            return $permissionCheck;
        }

        $paymentId = $request->input('payment_id');

        if (!$paymentId) {
            return redirect()->back()->with('error', 'Invalid payment ID');
        }

        // Find the payment record
        $payment = debtsModel::find($paymentId);

        if (!$payment) {
            return redirect()->back()->with('error', 'Payment not found');
        }

        // Verify shop access for non-admin users
        if ($user->levelStatus !== 'Admin') {
            $shopName = $request->input('shop', getSessionAccountDisplayName());
            if ($payment->account !== $shopName) {
                return redirect()->back()->with('error', 'You do not have permission to delete this payment');
            }
        }

        $deletedAmount = $payment->amount;
        $invoiceId = $payment->orderId;
        $shopName = $payment->account;

        // Delete the payment
        $payment->delete();

        // Log the deletion
        logModal::create([
            'title' => 'Payment Deleted',
            'description' => 'Payment of Tsh ' . number_format($deletedAmount) . ' for invoice ' . $invoiceId . ' deleted by ' . session('username')
        ]);

        return redirect()->back()->with('success', 'Payment deleted successfully');
    }

    /**
     * Create a manual invoice
     */
    public function createManualInvoice(Request $request)
    {
        $user = Auth::user();
        
        // Validate request
        $validated = $request->validate([
            'account' => 'required|string|max:255',
            'customer_id' => 'required|integer|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Verify account access for admin (admin can use any account, users are restricted)
        if ($user->levelStatus !== 'Admin') {
            // For non-admin, verify they have access to the selected account
            $hasAccess = \App\Models\UserAccount::where('user_id', $user->id)
                ->where('account', $validated['account'])
                ->exists();
            
            if (!$hasAccess) {
                return redirect()->back()->with('error', 'You do not have permission to create invoices for this shop');
            }
        }

        // Get customer details for the selected account
        $customer = customerModel::where('id', $validated['customer_id'])
            ->where('account', $validated['account'])
            ->first();

        if (!$customer) {
            return redirect()->back()->with('error', 'Customer not found in the selected shop');
        }

        try {
            DB::transaction(function () use ($validated, $customer, $user) {
                // Generate unique order ID and name
                $orderId = Uuid::uuid4();
                $orderName = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                
                // Get next invoice number for this shop/date
                $today = Carbon::parse($validated['invoice_date'])->toDateString();
                $lastInvoice = ordersModel::where('account', $validated['account'])
                    ->whereDate('created_at', $today)
                    ->where('orderName', 'like', 'INV-' . date('Ymd') . '%')
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($lastInvoice) {
                    // Extract sequence number and increment
                    $lastSeq = (int) substr($lastInvoice->orderName, -6);
                    $newSeq = $lastSeq + 1;
                    $orderName = 'INV-' . date('Ymd') . '-' . str_pad($newSeq, 6, '0', STR_PAD_LEFT);
                }

                // Create order record
                $order = ordersModel::create([
                    'order_id' => $orderId,
                    'stockId' => 'MANUAL',
                    'orderName' => $orderName,
                    'cName' => $validated['customer_name'],
                    'cPhone' => $customer->phone ?? $customer->id,
                    'productId' => 'MANUAL',
                    'pQuantity' => 1,
                    'productPrice' => $validated['amount'],
                    'totalPrice' => $validated['amount'],
                    'return_amount' => 0,
                    'credit' => 1,
                    'paid' => 0,
                    'transactionType' => 'Credit',
                    'status' => 'Debt',
                    'served_by' => $user->name ?? session('username'),
                    'account' => $validated['account'],
                    'coupons' => null,
                    'discount' => 0,
                    'discount_increase' => 0,
                    'offered_items' => 0,
                    'offer_parent_product' => null,
                ]);


                // Log the manual invoice creation
                logModal::create([
                    'title' => 'Manual Invoice',
                    'description' => "Manual invoice {$orderName} created for {$validated['customer_name']} - Tsh " . number_format($validated['amount']) . " by " . ($user->name ?? session('username')),
                ]);
            });

            return redirect()->back()->with('success', 'Manual invoice created successfully!');
        } catch (\Exception $e) {
            \Log::error('Manual invoice creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }
}