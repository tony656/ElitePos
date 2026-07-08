<?php

namespace App\Http\Controllers;
use App\Models\notifications;
use App\Models\productsModel;
use Illuminate\Http\Request;
use App\Models\logModal;
use App\Models\recevingModel;
use function getUserAccounts;

class notification extends Controller
{
    public function index() {

      $accounts = getUserAccounts();
      $accountIds = array_column($accounts, 'id');

      return redirect()->route('ai-agent.index');
    }

    public function notification(Request $req) {

        $to = $req->input('to');
        $message = $req->input('message');
        
        $notification = new notifications();
        $notification->head = 'Admin';
        $notification->target = $to;
        $notification->message = $message;
        $notification->save();

        $status = session('status');

        $data = notifications::where('head', '=', $status)
        ->orderBy('id', 'DESC')->get();

         $create = new logModal();
            $create->title = 'Notification Log';
            $create->description = 'Admin - '.$to .' (notification) Sent  Successfully By '.Auth::user()->name;
            $create->save();

        return redirect()->back()->with('success', 'Messega Sent');
    }

    public function delete(Request $req) {

        $id = $req->input('id');

        $delete = notifications::where('id', '=', $id)->delete();
        
        if($delete) {
            $create = new logModal();
            $create->title = 'Notification Log';
            $create->description = $id .' (notification) Deleted  Successfully By '.Auth::user()->name;
            $create->save();
        }
        return redirect()->back()->with('error', 'Messega Deleted');
    }
}
