<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\vendorModal;
use App\Models\logModal;
use App\Models\accountModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\recevingModel;
use function getCurrentShopId;
use function getUserAccounts;

class supplier extends Controller
{
    public function index(Request $req) {

    if (!canUser('view_suppliers')) {
        abort(403, 'Unauthorized access');
    }
        $user = Auth::user();
        $Account = getCurrentShopId();
        $shops = getUserAccounts();


        // Determine selected shop (for admin filter)
        $selectedShopId = $req->input('shop');
   

        // Build query
        $query = vendorModal::query();

            if ($selectedShopId) {
                $query->where('account', 7);
            }
        
        $fetch = $query->get();

        $data = compact(
            'fetch',
            'shops',
        );

            return view('main-suppliers', $data);
    
    }
}
