<?php

namespace App\Http\Controllers;

use App\Models\expensesModel;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Models\logModal;
use Illuminate\Support\Facades\Auth;
use function getSessionAccountName;
class expensesController extends Controller
{
    public function index(Request $req) {

        $user = Auth::user();

        if(!empty($req->input('selectedDate'))) {


            $thedate = $req->input('selectedDate');
            $start_date = $thedate . ' 00:00:00';
            $end_date = $thedate . ' 23:59:59';
        
            $expense = expensesModel::where('account', getSessionAccountName())->whereBetween('created_at', [$start_date, $end_date])->get();
        
        } else {

            $expense = expensesModel::where('account', getSessionAccountName())->get();

        }
        $data = compact(
        'expense'
    );

 if (strtolower(trim($user->levelStatus)) === 'admin') {
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
        $expenses->account = getSessionAccountName();

        if($expenses->save()) {
              $create = new logModal();
            $create->title = 'Expense Log';
            $create->description = $exName .'(expense) Added  Successfully By '.session('username');
            $create->save();
        }
        return redirect()->back()->with('success', 'Expense is added successfully');


    }
    public function dltExpense (Request $req) {
        $expenseId = $req->input('expenseId');

        $delt = expensesModel::where('id', $expenseId)->first();

        if($delt) {
                   $create = new logModal();
            $create->title = 'Expense Log';
            $create->description = $delt->expenseName .'(expense) Deleted  Successfully By '.session('username');
            $create->save();

            $delt->delete();

            return redirect()->back()->with('success', 'Expense deleted successfuly');
        } else {
            return redirect()->back()->with('success', 'Expense failed to delete');
        }
        
    }
}
