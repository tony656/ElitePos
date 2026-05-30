<?php

namespace App\Http\Controllers;

use App\Exports\ProductReportExport;
use App\Models\accountModel;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function exportProductReport()
    {
        $user = Auth::user();

        // Determine which accounts to query
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $accountIds = accountModel::pluck('id')->toArray();
        } else {
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
        }

        return Excel::download(new ProductReportExport($accountIds), 'product_report.xlsx');
    }
}
