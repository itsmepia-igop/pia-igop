<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryReport implements FromView, WithEvents, ShouldAutoSize
{
    use Exportable;

    protected $products;
    protected $raw_materials;
    
    public function __construct(
        $products,
        $raw_materials
    ) {
        $this->products = $products;
        $this->raw_materials = $raw_materials;
    }

    public function view(): View
    {
        return view('admin.reports.inventory-export', [
            'products' => $this->products,
            'raw_materials' => $this->raw_materials
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $columnsToExclude = ['B'];

                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    if (!in_array($column, $columnsToExclude)) {
                        $sheet->getColumnDimension($column)->setAutoSize(true);
                    } else {
                        $sheet->getColumnDimension($column)->setWidth(25);
                        $sheet->getStyle($column)->getAlignment()->setWrapText(true);
                    }
                }
            },
        ];
    }
}