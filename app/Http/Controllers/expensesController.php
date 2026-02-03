<?php

namespace App\Http\Controllers;

use App\Models\expensesModel;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Models\logModal;
use Illuminate\Support\Facades\Auth;
class expensesController extends Controller
{
    public function index(Request $req) {

        $user = Auth::user();

        if(!empty($req->input('selectedDate'))) {


            $thedate = $req->input('selectedDate');
            $start_date = $thedate . ' 00:00:00';
            $end_date = $thedate . ' 23:59:59';
        
            $expense = expensesModel::where('account', session('account'))->whereBetween('created_at', [$start_date, $end_date])->get();
        
        } else {

            $expense = expensesModel::where('account', session('account'))->get();

        }
        $data = compact(
        'expense'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.expenses', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.expenses', $data);
    }
    }
  
    public function expenseInsert(Request $req) {

        $exName = $req->input('exName') ?? 'Unknown';
        $exuser = $req->input('expuser') ?? 'Unknown';
        $category = $req->input('category') ?? 'Unknown';
        $amount = $req->input('amount');

        $uuid = Uuid::uuid4();

        $expenses = new expensesModel;
        $expenses->expense_id = $uuid;
        $expenses->expenseName = $exName;
        $expenses->expuser = $exuser;
        $expenses->category = $category;
        $expenses->amount = $amount;
        $expenses->account = session('account');

        if($expenses->save()) {
              $create = new logModal();
            $create->title = 'Expense Log';
            $create->description = $exName .'(expense) Added  Successfully By '.session('username');
            $create->save();
        }
        return redirect()->back()->with('success', 'Expense is added successfully');


    }
}
