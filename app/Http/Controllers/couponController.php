<?php

namespace App\Http\Controllers;

use App\Models\couponModel;
use Illuminate\Http\Request;
use App\Models\logModal;

class couponController extends Controller
{
    public function index() {

        $data = couponModel::where('account', session('account'))->orderBy('id', 'desc')->get();

        return view('coupons', compact('data'));
    }

    public function couponnew(Request $req) {
        $quantity = $req->input('quantity');
        $expire = $req->input('expire');
        $amount = $req->input('amount');

      
    $couponCode = [];

for ($i = 0; $i < $quantity; $i++) {
    $code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
    while (in_array($code, $couponCode)) {
        $code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
    }
    $couponCode[] = $code;
    $coupon = new couponModel();
    $coupon->couponCode = $code;
    $coupon->amount = $amount;
    $coupon->expire = $expire;
    $coupon->account = session('account');
    $coupon->save();
}
    if($coupon) {
         $create = new logModal();
            $create->title = 'Coupon Log';
            $create->description = $quantity .'(Coupons) Created  Successfully By '.session('username');
            $create->save();
    }
$data = couponModel::where('account', session('account'))->get();

return view('coupons', compact('data'));
}

public function deltCoupon(Request $req) {
    $coupId = $req->input('coupId');

    $dlt = couponModel::where('account', session('account'))->where('couponCode', '=', $coupId)->first();

    if($dlt) {
        $dlt->delete();
         $create = new logModal();
            $create->title = 'Coupon Log';
            $create->description = $coupId .'(Coupons) Deleted  Successfully By '.session('username');
            $create->save();

        return redirect()->back()->with('success', 'Coupon Deleted');
    }
      return redirect()->back()->with('success', 'Failed to Delete');
}
    }
