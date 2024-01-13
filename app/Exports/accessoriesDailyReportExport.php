<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class accessoriesDailyReportExport implements FromCollection, WithEvents
{

    use Exportable;
    protected $data;
    protected $header;

    public function __construct($header, $data){
        $this->data = $data;
        $this->header = $header;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $value = ['header' => $this->header, 'data' => $this->data];
            return collect([ $value ['header'], value($value['data'])->map(function ($item){
                return [
                    "ORDER NUMBER" => $item['order']['order_number'] ?? null,
                    "INVENTORY TYPE" => $item['inventory']['categories']['name'] ?? null,
                    "INVENTORY NAME" => $item['inventory']['name'],
                    "CASHIER" => $item['order']['user']['first_name']." ".$item['order']['user']['last_name'] ?? null, 
                    "Quantity" => $item['quantity'] ?? null, 
                    "Price" => $item['price'] ?? null,
                    'AMOUNT' => $item['amount'] ?? null, 
                    'DATE' => $item['created_at'] ?? null,
                  ];
                })
            ]);

    }

    public function registerEvents(): array 
    {
        $counter = $this->data->count();
        $sumQuantity = $this->data->sum('quantity');
        $sumPrice = $this->data->sum('price');
        $sumAmount = $this->data->sum('amount');

        return [
            AfterSheet::class => function(AfterSheet $event)
             use ($counter, $sumQuantity, $sumPrice,$sumAmount)
              {
            
                $event->sheet->setCellValue('E'.$counter+2, $sumQuantity)
                              ->setCellValue('F'.$counter+2, $sumPrice)
                              ->setCellValue('G'.$counter+2, $sumAmount);

              },
        ];
    }
}
