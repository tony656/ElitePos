<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\logModal;
use Illuminate\Http\Request;
use App\Models\usersModel;
use App\Models\accountModel;

class validationController extends Controller
{
    
    public function index()
    {
        return view('login');
    }

   public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (!Auth::attempt($credentials)) {
        return back()->with('error', 'Invalid email or password');
    }

    $request->session()->regenerate();

    $user = Auth::user();

    // Optional: update status
    $user->status = 'Online';
    $user->save();

    $fetch = accountModel::first();

    session([
        'account' => $user->account ?? 'Maili Moja Shop',
        'username' => $user->name,
    ]);

    $log = new logModal();
    $log->title = 'New Login';
    $log->description = $user->name . ' logged in on account ' . session('account');
    $log->save();

    /*
    |---------------------------------------
    | ROLE-BASED REDIRECT
    |---------------------------------------
    */
    if ($user->levelStatus === 'Admin') {
        return redirect()->route('admin.dashboard');
    }

    // All non-admin users
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
            $create->description = session('username') . '(User) logged out from the system ' . session('account');
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


    public function switch(Request $req) {
        $name = $req->input('account');

        session(['account' => $name]);

         $create = new logModal();
            $create->title = 'Switched Account';
            $create->description = $name .'(Account) Switched by '.session('username');
        $create->save();
        return redirect()->back()->with('success', 'Account Switched');
    }
}
