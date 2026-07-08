<?php

namespace App\Http\Controllers;

use App\Exports\ProductReportExport;
use App\Models\accountModel;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use function getuseraccounts;

class ReportController extends Controller
{
    public function exportProductReport()
    {
        $user = Auth::user();
        $shops = getuseraccounts();
        $accountIds = array_column($shops, 'id');

        return Excel::download(new ProductReportExport($accountIds), 'product_report.xlsx');
    }
}
