<?php

namespace App\Http\Controllers;
use App\Models\notifications;
use Illuminate\Http\Request;
use App\Models\logModal;

class notification extends Controller
{
    public function index() {

        $status = session('status');

        $data = notifications::where('head', '=', $status)
                            ->orderBy('id', 'DESC')->get();
                
        
        return view('notification', compact('data'));
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
            $create->description = 'Admin - '.$to .' (notification) Sent  Successfully By '.session('username');
            $create->save();

        return redirect()->back()->with('success', 'Messega Sent');
    }

    public function delete(Request $req) {

        $id = $req->input('id');

        $delete = notifications::where('id', '=', $id)->delete();
        
        if($delete) {
            $create = new logModal();
            $create->title = 'Notification Log';
            $create->description = $id .' (notification) Deleted  Successfully By '.session('username');
            $create->save();
        }
        return redirect()->back()->with('error', 'Messega Deleted');
    }
}
