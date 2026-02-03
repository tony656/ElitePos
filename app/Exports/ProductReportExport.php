<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\productsModel;

class ProductReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return productsModel::all();
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
