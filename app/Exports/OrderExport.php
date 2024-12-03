<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'Название продукта',
            'Кол-во',
            'Цена, руб.',
            'Сумма, руб.',
            'Итоговая стоимость',
        ];
    }

    public function map($row): array
    {
        $data = [
            $row->product->title,
            $row->amount,
            $row->price,
            $row->price * $row->amount,
            $row->order->price
        ];

        return $data;
    }

    public function collection()
    {
        return OrderDetail::where('order_id', $this->id)->with('product')->with('order')->get();
    }
}
