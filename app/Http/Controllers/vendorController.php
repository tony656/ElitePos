<?php

namespace App\Http\Controllers;
use App\Models\vendorModal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\logModal;
use App\Models\productsModel;

class vendorController extends Controller
{
    public function index() {
        $user = Auth::user();

        $Account = session('account');

        $fetch = vendorModal::where('account', $Account)->get();

        $data = compact(
        'fetch'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.vendors', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.vendors', $data);
    }
    }
    


    public function newVendor(Request $req) {

                $Account = session('account');

        $name = $req->input('name');
        $contact = $req->input('contact');
        $address = $req->input('address');
        $type = $req->input('type');
        $credit = $req->input('credit');
        $bank = $req->input('bank');
        $account = $req->input('account');
        $description = $req->input('description');

        $insert = new vendorModal();
        $insert->name = $name;
        $insert->location = $address;
        $insert->contact = $contact;
        $insert->description = $description;
        $insert->businessType = $type;
        $insert->credit = $credit;
        $insert->bank = $bank;
        $insert->card = $account;
        $insert->createdBy = session('username');
        $insert->account = $Account;
        $insert->save();

        if($insert) {

            $create = new logModal();
            $create->title = 'Vendor Created';
            $create->description = $name.'(Vendor) created by '.session('username');
            $create->save();

            return redirect()->back()->with('success', 'Vendor added successfully');
        } else {
              $create = new logModal();
            $create->title = 'Vendor Creation Failed';
            $create->description = $name.'(Vendor) creation Failed, by '.session('username');
            $create->save();

        return redirect()->back()->with('success', 'faild to add Vendor');

        }
    }

    public function dltVendeor(Request $req) {

                $Account = session('account');

        $prodId = $req->input('product_id');

         $deletes = vendorModal::where('account', $Account)->where('id', $prodId)->first();

        $delete = vendorModal::where('account', $Account)->where('id', $prodId)->delete();

        if($delete) {
             $create = new logModal();
            $create->title = 'Vendor Deleted';
            $create->description = $deletes->name .'(Vendor) Deleted By '.session('username');
            $create->save();
            
            return redirect()->back()->with('success', 'Vendor Deleted successfully');
        } else {
             $create = new logModal();
            $create->title = 'Vendor Deletion Failed';
            $create->description = $deletes->name .'(Vendor) Deletion Failed By '.session('username');
            $create->save();
            
            return redirect()->back()->with('success', 'Vendor failed to delete');
        }
    }

    public function viewVendor(Request $req) {

        $user = Auth::user();
        $vendorId = $req->input('vendorId');
        $Account = session('account');

        $fetchProduct = productsModel::where('supplier', $vendorId)->where('account', $Account)->get();
        $fetch = vendorModal::where('id', $vendorId)->where('account', $Account)->first();

            $data = compact(
        'fetch','fetchProduct'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.vendorView', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.vendorView', $data);
    }
    }
}
