<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CashierDailyExport implements FromCollection, WithEvents
{

    use Exportable;
    protected $data;
    protected $header;

    public function __construct($header, $data){
        $this->data = $data;
        $this->header = $header;
    }

    public function collection(): Collection
    {
        $value = ['header' => $this->header, 'data' => $this->data];
        return collect([ $value ['header'], value($value['data'])->map(function ($item){
            return [
                'ORDER NUMBER' => $item['orders']['order_number'] ?? null,
                'REFERENCE NUMBER' => $item['reference_number'] ?? null,
                'TOTAL AMOUNT'  => $item['amount'] ?? null,
                'DATE' => $item['transaction_date'] ?? null,
                'CASHIER' =>  implode(' ', array( ($item['user']['first_name']) ?? null , ($item['user']['last_name']) ?? null ))
            ];
         })
        ]);
    }

        public function registerEvents(): array 
    {
        $counter = $this->data->count();
        $sumAmount = $this->data->sum('amount');

        return [
            AfterSheet::class => function(AfterSheet $event) use ($counter, $sumAmount)
              {
                  $event->sheet->setCellValue('C'.$counter+2, $sumAmount);
              },
        ];
    }
}
