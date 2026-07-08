<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\logModal;
use Illuminate\Http\Request;
use App\Models\usersModel;
use App\Models\accountModel;
use App\Models\UserAccount;
use App\Models\salsModel;
use App\Models\ordersModel;
use App\Models\productsModel;
use Illuminate\Support\Facades\DB;
use function getSessionAccountName;
use App\Helpers\PermissionHelper;
class validationController extends Controller
{
    public function index()
    {
        // Get today's stats for all accounts (for login page dashboard)
        $today = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        
        // Get current hour for the chart
        $currentHour = now()->hour;
        
        // Today's revenue (all accounts)
        $todayRevenue = salsModel::whereBetween('created_at', [$today, $todayEnd])
            ->sum('totalPrice');
        
        // Today's transaction count
        $todayOrders = salsModel::whereBetween('created_at', [$today, $todayEnd])
            ->count();
        
        // Average ticket
        $avgTicket = $todayOrders > 0 ? round($todayRevenue / $todayOrders) : 0;
        
        // Transaction count (items sold would need product-level data)
        // For now, we use order count as proxy for items sold
        $itemsSold = $todayOrders;
        
        // Recent transactions (last 5)
        $recentTransactions = salsModel::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Yesterday's revenue for comparison
        $yesterday = now()->subDay()->startOfDay();
        $yesterdayEnd = now()->subDay()->endOfDay();
        $yesterdayRevenue = salsModel::whereBetween('created_at', [$yesterday, $yesterdayEnd])
            ->sum('totalPrice');
        
        // Calculate growth percentage
        $growthPercent = 0;
        if ($yesterdayRevenue > 0) {
            $growthPercent = round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1);
        } elseif ($todayRevenue > 0) {
            $growthPercent = 100;
        }
        
        // Hourly sales data for chart (last 24 hours)
        $hourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $hourStart = now()->startOfDay()->addHours($i);
            $hourEnd = $hourStart->copy()->endOfHour();
            
            $hourlyData[$i] = salsModel::whereBetween('created_at', [$hourStart, $hourEnd])
                ->sum('totalPrice');
        }
        
        // Normalize hourly data for chart display (0-100%)
        $maxHourly = max($hourlyData) > 0 ? max($hourlyData) : 1;
        $normalizedHeights = array_map(function($val) use ($maxHourly) {
            return round(($val / $maxHourly) * 100);
        }, $hourlyData);
        
        $data = [
            'todayRevenue' => $todayRevenue,
            'todayOrders' => $todayOrders,
            'avgTicket' => $avgTicket,
            'itemsSold' => $itemsSold,
            'recentTransactions' => $recentTransactions,
            'growthPercent' => $growthPercent,
            'normalizedHeights' => $normalizedHeights,
            'currentHour' => $currentHour,
        ];
        
        return view('login', $data);
    }

  public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);
    
    $remember = $request->has('remember');

    if (!Auth::attempt($credentials, $remember)) {
        return back()->with('error', 'Invalid email or password');
    }
    
    $request->session()->regenerate();

    $user = Auth::user();

    if($user->status === 'banned' || $user->status === 'deleted' || empty($user->status)) {

    return back()->with('error', 'You are not allowed to login');
    }
    // Optional: update status
    $user->status = 'Online';
    $user->save();

    // Create log entry
    $log = new logModal();
    $log->title = 'New Login';
    $log->description = $user->name . ' logged in ';
    $log->save();

    // Get user's accessible accounts with eager loading of account relationship
    if ($user->levelStatus === 'Admin') {
        // Admins have access to all accounts (ordered by name)
        $userAccounts = accountModel::orderBy('name', 'asc')->get()->map(function($account) use ($user) {
            return (object)[
                'account' => $account->id,
                'accountRel' => $account,
                'is_primary' => false
            ];
        });
    } else {
        // Regular users' accessible accounts (ordered by account name)
        $userAccounts = UserAccount::where('user_id', $user->id)
            ->with('accountRel')
            ->get()
            ->sortBy(function($userAccount) {
                return $userAccount->accountRel->name ?? '';
            });
    }
    
    // Backward compatibility
    if ($userAccounts->count() == 0 && $user->account) {
        UserAccount::create([
            'user_id' => $user->id,
            'account' => $user->account,
            'is_primary' => true,
        ]);
        $userAccounts = UserAccount::where('user_id', $user->id)
            ->with('accountRel')
            ->get();
    }
    
    // CHECK IF USER HAS ANY ACCOUNTS
    if ($userAccounts->count() == 0) {
        Auth::logout();
        return back()->with('error', 'No accounts assigned to this user. Please contact administrator.');
    }
    
    // CREATE ARRAY OF ACCESSIBLE ACCOUNTS WITH NAME & ID
    $accessibleAccounts = [];
    foreach ($userAccounts as $userAccount) {
        if ($userAccount->accountRel) {
            $accessibleAccounts[] = [
                'id' => $userAccount->accountRel->id,
                'name' => $userAccount->accountRel->name
            ];
        }
    }
    
    // Determine account ID (primary, or first available, or fallback to user's account)
    $primaryAccount = $userAccounts->where('is_primary', 1)->first();
    if ($primaryAccount) {
        $accountId = $primaryAccount->account;
    } elseif ($user->account) {
        $accountId = $user->account;
    } else {
        // Fallback: use first available account from user's accounts
        $firstAccount = $userAccounts->first();
        $accountId = $firstAccount ? $firstAccount->account : null;
    }
    
    // CRITICAL: Ensure we have an account ID for regular users
    if (!$accountId && $user->levelStatus !== 'Admin') {
        Auth::logout();
        return back()->with('error', 'No valid account found. Please contact administrator.');
    }

    // Resolve account ID to account NAME (string) for session storage
    $accountName = null;
    if ($accountId) {
        $account = accountModel::find($accountId);
        $accountName = $account ? $account->name : null;
    }
    
    // ========== FIX: Properly decode permissions ==========
    $rawPermissions = $user->permissions;
    $permissions = [];
    
    if (is_array($rawPermissions)) {
        // Already an array
        $permissions = $rawPermissions;
    } elseif (is_string($rawPermissions) && !empty($rawPermissions)) {
        // Try to decode JSON string
        $decoded = json_decode($rawPermissions, true);
        
        if (is_array($decoded)) {
            $permissions = $decoded;
        } else {
            // Try with stripslashes
            $decoded = json_decode(stripslashes($rawPermissions), true);
            if (is_array($decoded)) {
                $permissions = $decoded;
            } else {
                // Extract quoted strings using regex
                preg_match_all('/"([^"]+)"/', $rawPermissions, $matches);
                $permissions = $matches[1] ?? [];
            }
        }
    }
    
    // Ensure permissions is always an array
    if (!is_array($permissions)) {
        $permissions = [];
    }
    
    // Set session variables
    session([
        'account_id' => $accountId,
        'account_name' => $accountName,
        'accessible_accounts' => $accessibleAccounts,
        'user_permissions' => $permissions, // Now properly decoded array
    ]);


    return redirect()->route('dashboard');
}
    /**
     * Select account after login (when user has multiple accounts)
     */
    public function selectAccount(Request $request)
    {
        $request->validate([
            'account' => 'required|integer',
        ]);

        $user = Auth::user();
        // The form sends the account ID (numeric) from the radio button value
        // which matches the user_accounts.account column (stores account ID as string)
        $selectedAccountId = (int) $request->input('account');

        // Verify user has access to this account by the numeric ID
        $hasAccess = UserAccount::where('user_id', $user->id)
            ->where('account', $selectedAccountId)
            ->exists();

        if (!$hasAccess) {
            return back()->with('error', 'You do not have access to this account');
        }

        // Get account name for display and logging
        $account = accountModel::find($selectedAccountId);
        $accountName = $account ? $account->name : $selectedAccountId;
        
        session([
            'account' => $accountName,
            'account_id' => $selectedAccountId,
            'needs_account_selection' => false,
        ]);
        
        $log = new logModal();
        $log->title = 'Account Selected';
        $log->description = $user->name . ' selected account ' . $accountName;
        $log->save();


            return redirect()->route('dashboard');

    }
    
public function logoutAndRedirect()
{
    $user = Auth::user();
    try {
        if ($user) {
            $user = usersModel::find($user->id);
            if ($user) {
                $user->status = 'Offline';
                $user->save();
            }

            // Log the logout
            $create = new logModal();
            $name = $user ? $user->name : 'Unknown';
            $create->title = 'Logged Out';
            $create->description = $name . ' (User) logged out from the system';
            $create->save();
        }

        // Log out and clear session
        Auth::logout();
        Session::flush();

        return redirect()->route('login')->with('error', ' logged out Successfully.');
    } catch (\Exception $e) {
        Auth::logout();
        Session::flush();
        return redirect()->route('login')->with('error', 'Error during logout.');
    }
}

    /**
     * Show emergency login form (accessible during system shutdown/block)
     */
    public function emergencyLogin()
    {
        // If already logged in as admin, redirect to dashboard
        if (Auth::check() && strtolower(trim(Auth::user()->levelStatus)) === 'admin') {
            return redirect()->route('dashboard');
        }
        
        return view('emergency-login');
    }

    /**
     * Process emergency login (bypasses system shutdown/block)
     */
    public function processEmergencyLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'emergency_code' => 'required|string',
        ]);

        // Verify emergency code from .env
        $emergencyCode = env('EMERGENCY_ADMIN_PASSWORD');
        if (!$emergencyCode || $request->emergency_code !== $emergencyCode) {
            return back()->with('error', 'Invalid emergency access code.');
        }

        // Attempt normal authentication
        $credentials = $request->only('email', 'password');
        
        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Invalid email or password');
        }

        $user = Auth::user();
        
        // Only admins can use emergency login
        if (strtolower(trim($user->levelStatus)) !== 'admin') {
            Auth::logout();
            return back()->with('error', 'Emergency access is restricted to administrators only.');
        }

        // Regenerate session for security
        $request->session()->regenerate();

        // Get user's accessible accounts (same as regular login)
        $userAccounts = UserAccount::where('user_id', $user->id)
            ->with('accountRel')
            ->get();
        
        // Backward compatibility
        if ($userAccounts->count() == 0 && $user->account) {
            UserAccount::create([
                'user_id' => $user->id,
                'account' => $user->account,
                'is_primary' => true,
            ]);
            $userAccounts = UserAccount::where('user_id', $user->id)
                ->with('accountRel')
                ->get();
        }
        
        // Determine account ID (primary, or first available, or fallback to user's account)
        $primaryAccount = $userAccounts->where('is_primary', 1)->first();
        if ($primaryAccount) {
            $accountId = $primaryAccount->account;
        } elseif ($user->account) {
            $accountId = $user->account;
        } else {
            $firstAccount = $userAccounts->first();
            $accountId = $firstAccount ? $firstAccount->account : null;
        }
        
        // Resolve account name
        $accountName = null;
        if ($accountId) {
            $account = accountModel::find($accountId);
            $accountName = $account ? $account->name : null;
        }

        // Set all required session variables (same as regular login)
        session([
            'account' => $accountName,
            'account_id' => (int)$accountId,
            'username' => $user->name,
            'emergency_access' => true,
            'emergency_login_at' => now()->toISOString(),
            'emergency_expires_at' => now()->addMinutes(env('EMERGENCY_ACCESS_DURATION_MINUTES', 60))->toISOString(),
            'user_permissions' => PermissionHelper::getUserPermissions($user->id),
        ]);

        // Log emergency access
        $log = new logModal();
        $log->title = 'Emergency Access Used';
        $log->description = $user->name . ' used emergency login from IP: ' . $request->ip();
        $log->ip_address = $request->ip();
        $log->save();

        return redirect()->route('dashboard')
            ->with('success', 'Emergency access granted. Session will expire in ' . env('EMERGENCY_ACCESS_DURATION_MINUTES', 60) . ' minutes.');
    }

    /**
     * Check if current session has valid emergency access
     */
    public function checkEmergencyAccess()
    {
        if (!session('emergency_access')) {
            return false;
        }

        $expiresAt = session('emergency_expires_at');
        if ($expiresAt && now()->greaterThan(\Carbon\Carbon::parse($expiresAt))) {
            // Emergency access expired
            session()->forget(['emergency_access', 'emergency_login_at', 'emergency_expires_at']);
            return false;
        }

        return true;
    }

    /**
     * Extend emergency access session
     */
    public function extendEmergencyAccess(Request $request)
    {
        if (!session('emergency_access')) {
            return response()->json(['error' => 'No active emergency session'], 403);
        }

        $duration = env('EMERGENCY_ACCESS_DURATION_MINUTES', 60);
        session([
            'emergency_expires_at' => now()->addMinutes($duration)->toISOString(),
        ]);

        return response()->json([
            'message' => 'Emergency access extended',
            'expires_at' => session('emergency_expires_at'),
            'duration_minutes' => $duration
        ]);
    }

    public function switch(Request $req) {
        $accountId = $req->input('account'); // This is the account ID (numeric)
        
        // Verify account exists
        $account = accountModel::find($accountId);
        if (!$account) {
            return redirect()->back()->with('error', 'Account not found');
        }
        
        // Store account name and ID in session
        session([
            'account' => $account->name,
            'account_id' => $accountId,
        ]);

        $create = new logModal();
            $create->title = 'Switched Account';
            $create->description = $account->name .'(Account) Switched by '.Auth::user()->name;
        $create->save();
        return redirect()->back()->with('success', 'Account Switched to ' . $account->name);
    }
    
    public function storeSession(Request $request)
{
    $shopId = $request->input('shopId');

    session([
        'selected_shop_id' => $shopId,
    ]);

    return redirect()->back();
}
}

