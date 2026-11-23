<?php

namespace App\Http\Controllers;

use App\Models\systemModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\usersModel;
use App\Models\notifications;
use function getCurrentShopId;

class homeController extends Controller
{
    public function dashboard() {
        $user = Auth::user();
        $Account = getCurrentShopId();
        $status = session('status');

        $getName = systemModel::where('account', $Account)->first();

        $dataNotifications = notifications::where('account', $Account)
            ->where('head', '=', $status)
            ->orderBy('id', 'DESC')
            ->take(3)
            ->get();

        $data = compact(
            'getName',
            'dataNotifications',
        );

        $role = strtolower(trim($user->levelStatus));

        if (!empty($role)) {
            return view('home', $data);
        }

        abort(403, 'Unauthorized access');
    }
}
