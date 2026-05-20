<?php

namespace App\Http\Controllers;

use App\Models\usersModel;
use App\Models\UserAccount;
use App\Models\accountModel;
use Illuminate\Http\Request;
use App\Models\logModal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function getSessionAccountId;

class userController extends Controller
{

    public function index(Request $request) {
        $user = Auth::user();
        $currentAccount = getSessionAccountId();
        
        // Get shop filter from request
        $shopFilter = $request->query('shop', null);
        
        // Base query: get users excluding Admins
        $query = usersModel::where('levelStatus', '!=', 'Admin');
        
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admin can see all users (including themselves), optionally filtered by shop
            if ($shopFilter) {
                $query->where(function($q) use ($shopFilter) {
                    $q->where('account', $shopFilter)
                      ->orWhereHas('accounts', function($q2) use ($shopFilter) {
                          $q2->where('account', $shopFilter);
                      });
                });
            }
        } else {
            // Regular users can only see users from their assigned accounts
            // Get all account IDs the current user has access to
            $userAccountIds = $user->accounts()->pluck('account')->toArray();
            
            if ($shopFilter && in_array($shopFilter, $userAccountIds)) {
                // Filter by selected shop if it's in user's assigned accounts
                $query->where(function($q) use ($shopFilter) {
                    $q->where('account', $shopFilter)
                      ->orWhereHas('accounts', function($q2) use ($shopFilter) {
                          $q2->where('account', $shopFilter);
                      });
                });
            } else {
                // Show only users from accounts assigned to current user
                $query->where(function($q) use ($userAccountIds) {
                    $q->whereIn('account', $userAccountIds)
                      ->orWhereHas('accounts', function($q2) use ($userAccountIds) {
                          $q2->whereIn('account', $userAccountIds);
                      });
                });
            }
        }
        
        $users = $query->get();
        
        // Get accounts for dropdown based on user role
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admin sees all accounts
            $accounts = accountModel::all();
        } else {
            // Regular users see only their assigned accounts
            $accountIds = $user->accounts()->pluck('account')->toArray();
            $accounts = accountModel::whereIn('id', $accountIds)->get();
        }

        $data = compact(
            'users',
            'accounts',
            'shopFilter'
        );

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.employees', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.employees', $data);
        }
    }

    public function searchSeller(Request $request)
{
   
    $query = $request->query('query', '');
    $user = Auth::user();
    
    if (strlen($query) < 1) {
        return response()->json([]);
    }

    // Get selected shop from session
    $selectedShopId = session('selected_shop_id');
    
    // If no shop selected, fallback to session account
    if (!$selectedShopId) {
        $selectedShopId = getSessionAccountId();
    }
    
    // For non-admin users, verify they have access to the selected shop
    if (strtolower(trim($user->levelStatus)) !== 'admin') {
        $assignedAccountIds = $user->accounts()->pluck('account')->toArray();
        // Fallback to the account field in users table if no pivot records
        if (empty($assignedAccountIds)) {
            $assignedAccountIds = [$user->account];
        }
        if (!in_array($selectedShopId, $assignedAccountIds)) {
            // User doesn't have access to this shop, return empty results
            return response()->json([]);
        }
        // Use only the selected shop (not all assigned shops)
        $accountIds = [$selectedShopId];
    } else {
        // Admin: use selected shop or fallback
        $accountIds = [$selectedShopId];
    }
    
    $users = usersModel::whereIn('account', $accountIds)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'contact', 'email', 'account', 'levelStatus')
            ->limit(15)
            ->get();

    return response()->json($users);
}

    public function registerEmployee(Request $request) {
        // Validate input
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'age' => 'nullable|integer|min:1',
            'level' => 'required|in:Admin,Manager,Seller',
            'password1' => 'required|string|min:6',
            'password2' => 'required|string|same:password1',
            'photo' => 'nullable|image|max:2048',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'accounts' => 'nullable|array',
            'accounts.*' => 'integer|exists:accounts,id',
        ]);

        if($request->password1 == $request->password2) {
    $user = new usersModel();
    $user->name = $request->fname;
    $user->contact = $request->contact;
    $user->email = $request->email;
    $user->age = $request->age;
    $user->levelStatus = $request->level;
    $user->password = bcrypt($request->password1);
    $user->permissions = json_encode($request->permissions ?? []); // Store as JSON
    $user->account = getSessionAccountId(); // Primary account (for backward compatibility)

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photo->move(public_path('images'), $photoName);
        $user->userImg = $photoName;
    }

    $user->save();

    if($user) {
        // Create user_account associations
        $selectedAccounts = $request->accounts ?? [];
        if (!empty($selectedAccounts)) {
            foreach ($selectedAccounts as $account) {
                UserAccount::create([
                    'user_id' => $user->id,
                    'account' => $account,
                    'is_primary' => ($account === getSessionAccountId()),
                ]);
            }
        } else {
            // If no accounts selected, assign to current account by default
            UserAccount::create([
                'user_id' => $user->id,
                'account' => getSessionAccountId(),
                'is_primary' => true,
            ]);
        }

    $create = new logModal();
        $create->title = 'Registered User';
        $create->description = $request->fname .'(User) Created Successfully by '.session('username');
$create->save();
            return redirect()->back()->with('success', 'Employee registered successfully');
    } else {
 $create = new logModal();
            $create->title = 'Registered User Failed';
            $create->description = $request->fname .'(User) Failed to register by '.session('username');
    $create->save();
        return redirect()->back()->with('error', 'Employee registering Failed');

    }
    
} else {
    return redirect()->back()->with('error', 'Passwords do not match');
}
    }

    public function employeeView(Request $req) {
        $user = Auth::user();
        $currentAccount = getSessionAccountId();

        $employeeId = $req->input('employeeId') ?? $req->query('employeeId');

        if (!$employeeId) {
            $path = strtolower(trim($user->levelStatus)) === 'admin' ? '/admin/employees' : '/user/employees';
            return redirect($path)->with('error', 'Invalid request');
        }

        // Get user who has access to the current account (either as primary in users table or via user_accounts table)
        $users = usersModel::where('id', $employeeId)
            ->first();

        if (!$users) {
            $path = strtolower(trim($user->levelStatus)) === 'admin' ? '/admin/employees' : '/user/employees';
            return redirect($path)->with('error', 'Employee not found');
        }

        // Get all available accounts
        $accounts = accountModel::all();
        
        // Get user's current accounts
        $userAccounts = $users->accounts()->get();

        $data = compact(
            'users',
            'accounts',
            'userAccounts'
        );

        $role = strtolower(trim($user->levelStatus));
        if ($role === 'admin') {
            return view('admin.employeeView', $data);
        }
        
        if (!empty($user->levelStatus)) {
            return view('user.employeeView', $data);
        }
        
        abort(403, 'Unauthorized access');
    }

    public function updateEmployee(Request $request) {
        $employeeId = $request->input('employeeId');
        
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'contact' => 'required|string|max:20',
            'email' => 'required|email',
            'levelStatus' => 'required|in:Admin,Manager,Seller',
            'photo' => 'nullable|image|max:2048',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'accounts' => 'nullable|array',
            'accounts.*' => 'integer|exists:accounts,id',
        ]);

        $currentAccount = getSessionAccountId();

        // Get user to update - admins can update any user, others only from their accounts
        if (strtolower(trim(Auth::user()->levelStatus)) === 'admin') {
            // Admin can update any user
            $user = usersModel::where('id', $employeeId)->first();
        } else {
            // Regular users can only update users from their assigned accounts
            $user = usersModel::where('account', $currentAccount)
                ->orWhereHas('accounts', function ($query) use ($currentAccount) {
                    $query->where('account', $currentAccount);
                })
                ->where('id', $employeeId)
                ->first();
        }

        if (!$user) {
            return redirect()->back()->with('error', 'Employee not found or you do not have permission to edit');
        }

        // Prevent duplicate email on another user
        $emailExists = usersModel::where('email', $request->email)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            return redirect()->back()->with('error', 'Email is already in use by another user');
        }

        $user->name = $request->name;
        $user->age = $request->age;
        $user->contact = $request->contact;
        $user->email = $request->email;
        $user->levelStatus = $request->levelStatus;

        // Update permissions - replace with new set
        $newPermissions = $request->permissions ?? [];
        if (is_array($newPermissions)) {
            $user->permissions = json_encode(array_values($newPermissions));
        } else {
            $user->permissions = json_encode([]);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('images'), $photoName);
            $user->userImg = $photoName;
        }

        $user->save();

        // Update user accounts if selected
        $selectedAccounts = $request->accounts ?? [];
        if (!empty($selectedAccounts)) {
            // Remove existing accounts
            $user->accounts()->delete();
            
            // Create new associations with proper primary flag
            $firstAccount = true;
            foreach ($selectedAccounts as $accountId) {
                UserAccount::create([
                    'user_id' => $user->id,
                    'account' => $accountId,
                    'is_primary' => $firstAccount, // First account becomes primary
                ]);
                $firstAccount = false;
            }
            
            // Also update the main users.account field to the first selected account
            $user->account = $selectedAccounts[0];
            $user->save();
        } else {
            // If no accounts selected, ensure at least one account assignment exists
            // Use the user's current account or session account
            $existingAccount = $user->account ?: $currentAccount;
            $user->account = $existingAccount;
            $user->save();
            
            // Create a user_account entry if none exists
            $hasAccount = UserAccount::where('user_id', $user->id)->exists();
            if (!$hasAccount) {
                UserAccount::create([
                    'user_id' => $user->id,
                    'account' => $existingAccount,
                    'is_primary' => true,
                ]);
            }
        }

        $create = new logModal();
        $create->title = 'Updated User';
        $create->description = $request->name . '(User) Updated Successfully by ' . session('username');
        $create->save();

        // Redirect back to employee view with the employee ID
        return redirect()->back()->with('success', 'Employee updated successfully');
    }

    public function banUser(Request $request) {
        $employeeId = $request->input('employeeId');
        $currentAccount = getSessionAccountId();

        // Get user - admins can ban any user, others only from their accounts
        if (strtolower(trim(Auth::user()->levelStatus)) === 'admin') {
            $user = usersModel::where('id', $employeeId)->first();
        } else {
            $user = usersModel::where('account', $currentAccount)
                ->orWhereHas('accounts', function ($query) use ($currentAccount) {
                    $query->where('account', $currentAccount);
                })
                ->where('id', '=', $employeeId)
                ->first();
        }

        if ($user) {
            $user->status = $user->status == 'banned' ? 'active' : 'banned';
            $user->save();

            $action = $user->status == 'banned' ? 'Banned' : 'Unbanned';
            $create = new logModal();
            $create->title = $action . ' User';
            $create->description = $user->name . ' (' . $user->email . ') ' . $action . ' by ' . session('username');
            $create->save();

            return redirect()->back()->with('success', 'Employee ' . strtolower($action) . ' successfully');
        } else {
            return redirect()->back()->with('error', 'Employee not found');
        }
    }

    public function deleteUser(Request $request) {
        $employeeId = $request->input('employeeId');
        $currentAccount = getSessionAccountId();

        // Get user - admins can delete any user, others only from their accounts
        if (strtolower(trim(Auth::user()->levelStatus)) === 'admin') {
            $user = usersModel::where('id', $employeeId)->first();
        } else {
            $user = usersModel::where('account', $currentAccount)
                ->orWhereHas('accounts', function ($query) use ($currentAccount) {
                    $query->where('account', $currentAccount);
                })
                ->where('id', '=', $employeeId)
                ->first();
        }

        if ($user) {
            $user->status = 'deleted';
            $user->save();

            $create = new logModal();
            $create->title = 'Deleted User';
            $create->description = $user->name . ' (' . $user->email . ') Deleted by ' . session('username');
            $create->save();

            return redirect()->back()->with('success', 'Employee deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Employee not found');
        }
    }

    public function changePassword(Request $request) {
        $employeeId = $request->input('employeeId');
        $newPassword = $request->input('new_password');
        $confirmPassword = $request->input('confirm_password');
        $currentAccount = getSessionAccountId();

        // Validate that passwords match
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match');
        }

        // Validate password is not empty
        if (empty($newPassword)) {
            return redirect()->back()->with('error', 'Password cannot be empty');
        }

        // Get user - admins can change any user's password, others only from their accounts
        if (strtolower(trim(Auth::user()->levelStatus)) === 'admin') {
            $user = usersModel::where('id', $employeeId)->first();
        } else {
            $user = usersModel::where('account', $currentAccount)
                ->orWhereHas('accounts', function ($query) use ($currentAccount) {
                    $query->where('account', $currentAccount);
                })
                ->where('id', $employeeId)
                ->first();
        }

        if ($user) {
            $user->password = bcrypt($newPassword);
            $user->save();

            $create = new logModal();
            $create->title = 'Changed Password';
            $create->description = $user->name . ' (' . $user->email . ') Password changed by ' . session('username');
            $create->save();

            return redirect()->back()->with('success', 'Password changed successfully');
        } else {
            return redirect()->back()->with('error', 'Employee not found');
        }
    }
}

