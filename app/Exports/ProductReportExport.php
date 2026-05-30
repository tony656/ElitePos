<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\productsModel;

class ProductReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $accountIds;

    public function __construct($accountIds = null)
    {
        $this->accountIds = $accountIds;
    }

    public function collection()
    {
        $query = productsModel::query();
        if ($this->accountIds && is_array($this->accountIds) && count($this->accountIds) > 0) {
            $query->whereIn('account', $this->accountIds);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return ['Product Code', 'Name', 'Quantity', 'Price', 'Stock', 'expire'];
    }

    public function map($product): array
    {
        return [
            $product->product_id,
            $product->name01 . ' ' . $product->name02,
            $product->quantity,
            $product->sPrice,
            $product->stock,
            $product->expire,
        ];
    }
}
