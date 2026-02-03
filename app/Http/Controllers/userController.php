<?php

namespace App\Http\Controllers;

use App\Models\usersModel;
use Illuminate\Http\Request;
use App\Models\logModal;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{

    public function index() {
        $user = Auth::user();
        $users = usersModel::where('account', session('account'))->get();

        $data = compact(
        'users'
    );



         $data = compact(
        'users'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.employees', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.employees', $data);
    }
    }

    public function registerEmployee(Request $request) {

        $fname =$request->input('fname');
        $contact =$request->input('contact');
        $email =$request->input('email');
        $age =$request->input('age');
        $level =$request->input('level');
        $password1 =$request->input('password1');
        $password2 =$request->input('password2');
        $permissions = $request->input('permissions', []); // Default to empty array

        if($password1 == $password2) {
    $user = new usersModel();
    $user->name = $fname;
    $user->contact = $contact;
    $user->email = $email;
    $user->age = $age;
    $user->levelStatus = $level;
    $user->password = bcrypt($password1);
    $user->permissions = json_encode($permissions); // Store as JSON
    $user->account = session('account');

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photo->move(public_path('images'), $photoName);
        $user->userImg = $photoName;
    }

    $user->save();

    if($user) {
        $create = new logModal();
            $create->title = 'Registered User';
            $create->description = $fname .'(User) Created Successfully by '.session('username');
$create->save();
            return redirect()->back()->with('success', 'Employee registered successfully');
    } else {
 $create = new logModal();
            $create->title = 'Registered User Failed';
            $create->description = $fname .'(User) Failed to register by '.session('username');
            $create->save();
        return redirect()->back()->with('error', 'Employee registering Failed');

    }
    
} else {
    return redirect()->back()->with('error', 'Passwords do not match');
}
    }

    public function employeeView(Request $req) {
        $user = Auth::user();

        $employeeId = $req->input('employeeId');

        if (!$employeeId) {
            return redirect('admin/employees')->with('error', 'Invalid request');
        }

        $users = usersModel::where('account', session('account'))->where('id', '=', $employeeId)->first();

        if (!$users) {
            return redirect('admin/employees')->with('error', 'Employee not found');
        }

        $data = compact(
        'users'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.employeeView', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.employeeView', $data);
    }

    }

    public function updateEmployee(Request $request) {
        $employeeId = $request->input('employeeId');
        $name = $request->input('name');
        $age = $request->input('age');
        $contact = $request->input('contact');
        $email = $request->input('email');
        $levelStatus = $request->input('levelStatus');
        $permissions = $request->input('permissions', []); // Default to empty array

        $user = usersModel::where('account', session('account'))->where('id', '=', $employeeId)->first();

      if ($user) {
    $user->name = $name;
    $user->age = $age;
    $user->contact = $contact;
    $user->email = $email;
    $user->levelStatus = $levelStatus;

    $existingPermissions = json_decode($user->permissions, true) ?? [];
    $newPermissions = $request->input('permissions', []);

    $user->permissions = json_encode(
        array_values(array_unique(array_merge($existingPermissions, $newPermissions)))
    );

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('images'), $photoName);
                $user->userImg = $photoName;
            }

            $user->save();

            $create = new logModal();
            $create->title = 'Updated User';
            $create->description = $name . '(User) Updated Successfully by ' . session('username');
            $create->save();

            return redirect()->back()->with('success', 'Employee updated successfully');
        } else {
            return redirect()->back()->with('error', 'Employee not found');
        }
    }

    public function banUser(Request $request) {
        $employeeId = $request->input('employeeId');

        $user = usersModel::where('account', session('account'))->where('id', '=', $employeeId)->first();

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

        $user = usersModel::where('account', session('account'))->where('id', '=', $employeeId)->first();

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
}

