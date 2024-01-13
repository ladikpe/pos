<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class inventoryHistoryReportExport implements FromCollection, WithEvents
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
                'NAME' => $item['inventory']['name'] ?? null,
                'QUANTITY' => $item['quantity'],
                'TOTAL AMOUNT'  => $item['price'],
                'DATE' =>  Carbon::parse($item['created_at'])->format('d-m-Y'),
                'TIME' => Carbon::parse($item['created_at'])->format('H:i:s'),
            ];
        })
        ]);
    }


  public function registerEvents(): array
    {
        $counter = $this->data->count();
        $sumQuantity = $this->data->sum('quantity');
        $sumAmount = $this->data->sum('price');

        return [
            AfterSheet::class => function(AfterSheet $event)
             use ($counter, $sumQuantity, $sumAmount)
              {
                    $event->sheet->setCellValue('B'.$counter+2, $sumQuantity)
                              ->setCellValue('C'.$counter+2, $sumAmount);

              },
        ];
    }

}
