<?php

namespace App\Http\Controllers;

use App\Exports\ProductReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function exportProductReport()
    {
        return Excel::download(new ProductReportExport, 'product_report.xlsx');
    }
}
