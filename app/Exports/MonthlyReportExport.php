<?php

namespace App\Exports;

use App\Models\YourModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\salsModel;
use App\Models\productsModel;

class MonthlyReportExport implements FromView
{  protected $sales;
    protected $start_date;
    protected $end_date;
    protected $Tdiscount;
    protected $Tsale;
    protected $Tproduct;
    protected $TNetProfit;

    public function __construct($sales, $start_date, $end_date, $Tdiscount, $Tsale, $Tproduct, $TNetProfit)
    {
        $this->sales = $sales;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->Tdiscount = $Tdiscount;
        $this->Tsale = $Tsale;
        $this->Tproduct = $Tproduct;
        $this->TNetProfit = $TNetProfit;
    }

    public function view(): View
    {
        return view('exports.sales-report', [
            'sales' => $this->sales,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'Tdiscount' => $this->Tdiscount,
            'Tsale' => $this->Tsale,
            'Tproduct' => $this->Tproduct,
            'TNetProfit' => $this->TNetProfit,
        ]);
    }
}
