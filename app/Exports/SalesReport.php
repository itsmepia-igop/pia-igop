<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesReport implements FromView, WithEvents, ShouldAutoSize
{
    use Exportable;

    private $orders;
    private $date;

    public function __construct(
        $orders,
        $date
    ) {
        $this->orders = $orders;
        $this->date = $date;
    }

    public function view(): View
    {
        return view('admin.reports.sales-export', [
            'orders' => $this->orders,
            'date' => $this->date
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
