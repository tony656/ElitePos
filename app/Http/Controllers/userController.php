<?php

namespace App\Http\Controllers;

use App\Models\usersModel;
use App\Models\UserAccount;
use App\Models\accountModel;
use Illuminate\Http\Request;
use App\Models\logModal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function getCurrentShopId;
use function getUserAccounts;
use App\Helpers\PermissionHelper;

class userController extends Controller
{
    private function isAdmin($user = null)
    {
        $user = $user ?? Auth::user();
        return $user && in_array(strtolower(trim($user->levelStatus)), ['admin', 'admin2']);
    }

    public function index(Request $request) {
        $user = Auth::user();
        $currentAccount = getCurrentShopId();
        
        // Get shop filter from request
        $shopFilter = $request->query('shop', null);
        
        // Base query: get users excluding Admins
        $query = usersModel::query();
        
        // Admins can see all users (including themselves), optionally filtered by shop
            if ($shopFilter) {
                $query->where(function($q) use ($shopFilter) {
                    $q->where('account', $shopFilter)
                      ->orWhereHas('accounts', function($q2) use ($shopFilter) {
                          $q2->where('account', $shopFilter);
                      });
                });
            }
    
        
        $users = $query->get();

            // Admin sees all accounts
            $accounts = getUserAccounts();
        

        $data = compact(
            'users',
            'accounts',
            'shopFilter'
        );

            return view('employees', $data);

    
    }

   public function searchSeller(Request $request)
{
    $query = $request->query('query', '');
    $user = Auth::user();
    
    if (strlen($query) < 1) {
        return response()->json([]);
    }

    // Get all accessible accounts for the user
    $accounts = getUserAccounts(); // Returns array of accounts with id & name
  
        $accountIds = getCurrentShopId();
    
    
    $users = usersModel::where('account', $accountIds)
        ->where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('contact', 'LIKE', "%{$query}%");
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
    $user->account = getCurrentShopId(); // Primary account (for backward compatibility)

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photo->move(public_path('images'), $photoName);
        $user->userImg = $photoName;
    }

    $user->save();

    PermissionHelper::savePermissions($user->id, $request->permissions ?? []);

    if($user) {
        // Create user_account associations
        $selectedAccounts = $request->accounts ?? [];
        if (!empty($selectedAccounts)) {
            foreach ($selectedAccounts as $account) {
                UserAccount::create([
                    'user_id' => $user->id,
                    'account' => $account,
                    'is_primary' => ($account === getCurrentShopId()),
                ]);
            }
        } else {
            // If no accounts selected, assign to current account by default
            UserAccount::create([
                'user_id' => $user->id,
                'account' => getCurrentShopId(),
                'is_primary' => true,
            ]);
        }

    $create = new logModal();
        $create->title = 'Registered User';
        $create->description = $request->fname .'(User) Created Successfully by '.Auth::user()->name;
$create->save();
            return redirect()->back()->with('success', 'Employee registered successfully');
    } else {
 $create = new logModal();
            $create->title = 'Registered User Failed';
            $create->description = $request->fname .'(User) Failed to register by '.Auth::user()->name;
    $create->save();
        return redirect()->back()->with('error', 'Employee registering Failed');

    }
    
} else {
    return redirect()->back()->with('error', 'Passwords do not match');
}
    }

    public function employeeView(Request $req, $employeeId = null) {
        $user = Auth::user();
        $currentAccount = getCurrentShopId();

        if (!$employeeId) {
            $employeeId = $req->input('employeeId') ?? $req->query('employeeId');
        }

        if (!$employeeId) {
            $path = $this->isAdmin($user) ? '/employees' : '/user/employees';
            return redirect($path)->with('error', 'Invalid request');
        }

        $employee = usersModel::where('id', $employeeId)
            ->first();

        if (!$employee) {
            $path = $this->isAdmin($user) ? '/employees' : '/user/employees';
            return redirect($path)->with('error', 'Employee not found');
        }

        $accounts = accountModel::all();
        $userAccounts = $employee->accounts()->get();
        $allPermissions = session('user_permissions');

        $data = compact(
            'employee',
            'accounts',
            'userAccounts',
            'allPermissions'
        );

            return view('employeeView', $data);
     

        
        abort(403, 'Unauthorized access');
    }
public function employeeDelete(Request $req) {
        $user = Auth::user();
        $currentAccount = getCurrentShopId();

        $employeeId = $req->input('employeeId') ?? $req->query('employeeId');

        if (!$employeeId) {
            $path = $this->isAdmin($user) ? '/employees' : '/user/employees';
            return redirect($path)->with('error', 'Invalid request');
        }

        // Get user who has access to the current account (either as primary in users table or via user_accounts table)
        $users = usersModel::where('id', $employeeId)
            ->delete();

        if (!$users) {
            $path = $this->isAdmin($user) ? '/employees' : '/user/employees';
            return redirect($path)->with('error', 'Employee not found');
        }
      return redirect()->back()->with('success', 'Employee deleted successfully');
    
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

    $currentAccount = getCurrentShopId();

    // Get user to update - admins can update any user, others only from their accounts
    if ($this->isAdmin()) {
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
    
    // ========== FIX: Save permissions as clean JSON array ==========
    $newPermissions = $request->permissions ?? [];
    if (is_array($newPermissions)) {
        // Get the array values to reindex
        $permissionValues = array_values($newPermissions);
        // Encode to JSON without escaping slashes
        $user->permissions = json_encode($permissionValues, JSON_UNESCAPED_SLASHES);
    } else {
        $user->permissions = json_encode([], JSON_UNESCAPED_SLASHES);
    }
    
    // Remove the duplicate line that was causing issues
     $user->permissions = $permissionValues; // REMOVE THIS DUPLICATE LINE
    
    // Handle photo upload
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photo->move(public_path('images'), $photoName);
        $user->userImg = $photoName;
    }

    $user->save();

    // Save permissions to the helper table
    PermissionHelper::savePermissions($user->id, $newPermissions);

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
                'is_primary' => $firstAccount,
            ]);
            $firstAccount = false;
        }
        
        // Also update the main users.account field to the first selected account
        $user->account = $selectedAccounts[0];
        $user->save();
    } else {
        // If no accounts selected, ensure at least one account assignment exists
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
    $create->description = $request->name . '(User) Updated Successfully by ' . Auth::user()->name;
    $create->save();

    return redirect('/employeeView/' . $user->id)->with('success', 'Employee updated successfully');
}

    public function banUser(Request $request) {
        $employeeId = $request->input('employeeId');
        $currentAccount = getCurrentShopId();

        // Get user - admins can ban any user, others only from their accounts
        if ($this->isAdmin()) {
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
            $create->description = $user->name . ' (' . $user->email . ') ' . $action . ' by ' . Auth::user()->name;
            $create->save();

            return redirect()->back()->with('success', 'Employee ' . strtolower($action) . ' successfully');
        } else {
            return redirect()->back()->with('error', 'Employee not found');
        }
    }

    public function deleteUser(Request $request) {
        $employeeId = $request->input('employeeId');
        $currentAccount = getCurrentShopId();

        // Get user - admins can delete any user, others only from their accounts
        if ($this->isAdmin()) {
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
            $create->description = $user->name . ' (' . $user->email . ') Deleted by ' . Auth::user()->name;
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
        $currentAccount = getCurrentShopId();

        // Validate that passwords match
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match');
        }

        // Validate password is not empty
        if (empty($newPassword)) {
            return redirect()->back()->with('error', 'Password cannot be empty');
        }

        // Get user - admins can change any user's password, others only from their accounts
        if ($this->isAdmin()) {
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
            $create->description = $user->name . ' (' . $user->email . ') Password changed by ' . Auth::user()->name;
            $create->save();

            return redirect()->back()->with('success', 'Password changed successfully');
        } else {
            return redirect()->back()->with('error', 'Employee not found');
        }
    }
}

