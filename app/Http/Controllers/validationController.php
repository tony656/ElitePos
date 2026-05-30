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
     // Check if sign-ins are blocked system-wide
     $systemSettings = \App\Models\systemModel::first();
     if ($systemSettings && $systemSettings->block_signins) {
         return back()->with('error', 'All sign-ins are currently blocked by the system administrator. Please try again later.');
     }
     
     $credentials = $request->validate([
         'email' => 'required|email',
         'password' => 'required|min:6',
     ]);
     
     $remember = $request->has('remember');

     if (!Auth::attempt($credentials, $remember)) {
         return back()->with('error', 'Invalid email or password');
     }
    
    // Get user IP Address
    $userIp = $request->ip();
    
    // Get precise location from browser if available
    $latitude = $request->input('latitude');
    $longitude = $request->input('longitude');
    $accuracy = $request->input('accuracy');
    $preciseLocation = $request->input('precise_location');
    
    // Get client timezone from browser
    $clientTimezone = $request->input('client_timezone');
    
    // Full Location detection with IP geolocation as fallback
    $country = 'Tanzania';
    $region = 'Unknown';
    $district = 'Unknown';
    $city = 'Unknown';
    $street = 'Unknown';
    $timezone = $clientTimezone ?? 'Africa/Nairobi';
    $locationString = 'Unknown Location';
    $locationSource = 'IP Geolocation';
    
    // If we have precise GPS coordinates, use reverse geocoding
    if ($latitude && $longitude) {
        try {
            // Use OpenStreetMap Nominatim for reverse geocoding (free, no API key needed)
            $reverseGeoUrl = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $reverseGeoUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Salstech/1.0'); // Required by Nominatim
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            if ($response) {
                $geoData = json_decode($response, true);
                
                if (isset($geoData['address'])) {
                    $address = $geoData['address'];
                    
                    $country = $address['country'] ?? $country;
                    $region = $address['state'] ?? $address['region'] ?? 'Unknown';
                    $district = $address['county'] ?? $address['district'] ?? 'Unknown';
                    $city = $address['city'] ?? $address['town'] ?? $address['village'] ?? 'Unknown';
                    $street = $address['road'] ?? $address['pedestrian'] ?? 'Unknown';
                    
                    // Build detailed location string
                    $locationParts = [];
                    if ($street && $street !== 'Unknown') $locationParts[] = $street;
                    if ($city && $city !== 'Unknown') $locationParts[] = $city;
                    if ($district && $district !== 'Unknown') $locationParts[] = $district;
                    if ($region && $region !== 'Unknown') $locationParts[] = $region;
                    if ($country && $country !== 'Unknown') $locationParts[] = $country;
                    
                    $locationString = implode(', ', $locationParts);
                    $locationString .= " (GPS: {$latitude}, {$longitude} ±{$accuracy}m)";
                    $locationSource = 'GPS (Precise)';
                }
            }
        } catch (\Exception $e) {
            // Fall back to IP geolocation if reverse geocoding fails
            $locationString = "GPS: {$latitude}, {$longitude} (Accuracy: {$accuracy}m) - Reverse geocoding failed";
        }
    } else {
        // Fallback to IP-based geolocation if GPS not available
        try {
            $geoUrl = "http://ip-api.com/json/" . $userIp . "?fields=status,country,countryCode,regionName,city,district,zip,timezone,isp,org,query";
            $geoData = @file_get_contents($geoUrl, false, stream_context_create([
                'http' => ['timeout' => 2]
            ]));
            
            if ($geoData !== false) {
                $geo = json_decode($geoData, true);
                
                if ($geo && isset($geo['status']) && $geo['status'] === 'success') {
                    $country = $geo['country'] ?? 'Tanzania';
                    $region = $geo['regionName'] ?? 'Unknown';
                    $district = $geo['district'] ?? 'Unknown';
                    $city = $geo['city'] ?? 'Unknown';
                    
                    $locationParts = [];
                    if ($city && $city !== 'Unknown') $locationParts[] = $city;
                    if ($district && $district !== 'Unknown') $locationParts[] = $district;
                    if ($region && $region !== 'Unknown') $locationParts[] = $region;
                    if ($country && $country !== 'Unknown') $locationParts[] = $country;
                    
                    $locationString = implode(', ', $locationParts);
                    
                    if (!$clientTimezone && isset($geo['timezone'])) {
                        $timezone = $geo['timezone'];
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback gracefully
        }
    }
    
    // Get client details
    $userAgent = $request->userAgent();

    $request->session()->regenerate();

    $user = Auth::user();

    // Optional: update status
    $user->status = 'Online';
    $user->save();

    // Store location metadata in session
    session([
        'login_ip' => $userIp,
        'login_timezone' => $timezone,
        'login_country' => $country,
        'login_region' => $region,
        'login_district' => $district,
        'login_city' => $city,
        'login_street' => $street,
        'login_location' => $locationString,
        'login_latitude' => $latitude,
        'login_longitude' => $longitude,
        'login_accuracy' => $accuracy,
        'location_source' => $locationSource,
        'user_agent' => $userAgent,
        'login_at' => now()
    ]);

    // Create log entry
    $log = new logModal();
    $log->title = 'New Login';
    $log->description = $user->name . ' logged in from IP: ' . $userIp . 
                       ' | Location Source: ' . $locationSource .
                       ' | Location: ' . $locationString . 
                       ' | Timezone: ' . $timezone;
    $log->ip_address = $userIp;
    $log->region = $locationString;
    $log->country = $country;
    $log->city = $city;
    $log->district = $district;
    $log->save();

    // Get user's accessible accounts with eager loading of account relationship
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
        
    } else {
        // Fallback: use first available account from user's accounts
        $firstAccount = $userAccounts->first();
        $accountId = $firstAccount ? $firstAccount->account : null;
    }

    // Resolve account ID to account NAME (string) for session storage
    // Keep 'account' as name for display purposes
    $accountName = null;
    if ($accountId) {
        $account = accountModel::find($accountId);
        $accountName = $account ? $account->name : null;
    }

    session([
        'account' => $accountName,           // Store name for display
        'account_id' => (int)$accountId,      // Store ID for database queries
        'username' => $user->name,
    ]);

   

    // Role-based redirect for single account users (case-insensitive)
    if (strtolower(trim($user->levelStatus)) === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
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

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }
    
public function logoutAndRedirect()
{
    $user = Auth::user();
    try {
        // Update user status
        $user = usersModel::find($user->id);
        if ($user) {
            $user->status = 'Offline';
            $user->save();
        }

        // Log the logout
        $create = new logModal();
            $create->title = 'Logged Out';
            $create->description = session('username') . '(User) logged out from the system ' . getSessionAccountDisplayName();
        $create->save();
        
  

        // Log out and clear session
        Auth::logout();
        Session::flush();

        return redirect()->route('login')->with('error', 'You have been logged out.');
    } catch (\Exception $e) {
        // Optional: handle errors
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
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.emergency-login');
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
        ]);

        // Log emergency access
        $log = new logModal();
        $log->title = 'Emergency Access Used';
        $log->description = $user->name . ' used emergency login from IP: ' . $request->ip();
        $log->ip_address = $request->ip();
        $log->save();

        return redirect()->route('admin.dashboard')
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
            $create->description = $account->name .'(Account) Switched by '.session('username');
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


