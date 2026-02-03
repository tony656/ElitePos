<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\logModal;
use Illuminate\Http\Request;

class logController extends Controller
{
    public function index() {
        $user = Auth::user();

        $fetch = logModal::orderBy('id', 'desc')->get();

        $data = compact(
        'fetch'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.logs', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.logs', $data);
    }
    }
}
