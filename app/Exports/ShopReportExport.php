<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ShopReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $shopReports;
    protected $totals;
    protected $dateParam;

    public function __construct($shopReports, $totals, $dateParam)
    {
        $this->shopReports = $shopReports;
        $this->totals = $totals;
        $this->dateParam = $dateParam;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->shopReports;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'Shop Name',
            'Location',
            'Transactions',
            'Cash Sales',
            'Credit Sales',
            'Total Sales',
            'Cash Return',
            'Credit Return',
            'Returned Receivings',
            'Discount',
            'Expenses',
            'Paid Invoices',
            'Profit/Loss',
            'Cash Receivings',
            'Credit Receivings',
            'Paid Receivings',
            'Cash Amount',
            'Cash Submitted',
            'Difference',
            'Status',
        ];
    }

    /**
     * @param mixed $shop
     * @return array
     */
    public function map($shop): array
    {
        static $index = 0;
        $index++;

        $profitClass = $shop->profit > 0 ? 'Profit' : ($shop->profit < 0 ? 'Loss' : 'Break Even');
        $diffClass = $shop->cash_difference > 0 ? 'Underpaid' : ($shop->cash_difference < 0 ? 'Overpaid' : 'Settled');

        return [
            $index,
            $shop->shop_name,
            $shop->location,
            $shop->total_transactions,
            $shop->cash_sales,
            $shop->credit_sales,
            $shop->total_sales,
            $shop->cash_return ?? 0,
            $shop->credit_return ?? 0,
            $shop->returned_receivings ?? 0,
            $shop->discount,
            $shop->expenses,
            $shop->paid_invoices,
            $shop->profit,
            $shop->cash_receivings,
            $shop->credit_receivings,
            $shop->paid_receivings,
            $shop->cash_amount,
            $shop->cash_submitted,
            $shop->cash_difference,
            $diffClass,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
