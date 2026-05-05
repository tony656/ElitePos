<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\logModal;
use Illuminate\Http\Request;

class logController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();

        $query = logModal::orderBy('id', 'desc');

        // Filter by date - default to current date
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('created_at', $request->date);
        } else {
            $query->whereDate('created_at', now()->toDateString());
        }

        $fetch = $query->get();

        $data = compact('fetch');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.logs', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.logs', $data);
        }
    }
}
