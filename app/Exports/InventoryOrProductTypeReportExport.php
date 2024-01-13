<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class InventoryOrProductTypeReportExport implements FromCollection, WithEvents
{

    use Exportable;
    protected $data;
    protected $header;

    public function __construct($header, $data){
        $this->data = $data;
        $this->header = $header;
    }

    public function collection()
    {
        $value = ['header' => $this->header, 'data' => $this->data];
        return collect([ $value ['header'], value($value['data'])->map(function ($item){
            return [
                'DATE/TIME' => $item['order']['order_date'] ?? null,
                'ORDER NUMBER' => $item['order']['order_number'] ?? null,
                'CASHIER' => $item['order']['employee']['first_name'] ?? null . ''. $item['order']['employee']['last_name'],
                'PRODUCT TYPE' => $item['inventory']['categories']['name'] ?? null,
                'CYLINDER TYPE' => $item['inventory']['name'] ?? null,
                'QUANTITY' => $item['quantity'],
                'TOTAL_LPG_SOLD' => (int)$item['quantity'] * (int)$item['price'],
                'PRICE' => $item['price'],
                'TOTAL AMOUNT'  => $item['order']['total'] ?? null,
            ];
        })
        ]);
    }


    public function registerEvents(): array
    {
        $counter = $this->data->count();
        $sumQuantity = $this->data->sum('quantity');
        $sumPrice = $this->data->sum('price');
        $sumAmount = $this->data->sum(function($item){
            return $item['order']['total'];
        });

        return [
            AfterSheet::class => function(AfterSheet $event)
             use ($counter, $sumQuantity, $sumPrice, $sumAmount)
              {

                $event->sheet->setCellValue('F'.$counter+2, $sumQuantity)
                              ->setCellValue('H'.$counter+2, $sumPrice)
                              ->setCellValue('I'.$counter+2, $sumAmount);
              },
        ];
    }


}


