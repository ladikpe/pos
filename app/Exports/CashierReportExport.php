<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CashierReportExport implements FromCollection, WithEvents
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
                    'STAFF ID'  => $item['user']['staff_id'] ?? null,
                    'USER NAME' => collect($item['user'])->get('first_name')  ?? null . " ".  collect($item['user'])->get('last_name') ?? null,
                    'CASH' => $item['cash'] ?? null,
                    'POS' => $item['pos'] ?? null,
                    "TRANSFER" => $item['transfer'] ?? null,
                    "PAY LATER" => $item['pay_later'] ?? null,
                    'AMOUNT' => $item['total_amount'] ?? null,
                    'DATE' => $item['transaction_date'] ?? null,
                ];
                })
            ]);

        }

   public function registerEvents(): array
    {
        $counter = $this->data->count();
        $sumPos = $this->data->sum('pos');
        $sumCash = $this->data->sum('cash');
        $sumTransfer = $this->data->sum('transfer');
        $sumPayLater = $this->data->sum('pay_later');
        $sumAmount = $this->data->sum('total_amount');


        return [
            AfterSheet::class => function(AfterSheet $event)
             use ($counter, $sumPos, $sumCash, $sumTransfer, $sumPayLater, $sumAmount) {

                $event->sheet->setCellValue('C'.$counter+2, $sumCash)
                              ->setCellValue('D'.$counter+2, $sumPos)
                              ->setCellValue('E'.$counter+2, $sumTransfer)
                              ->setCellValue('F'.$counter+2, $sumPayLater)
                              ->setCellValue('G'.$counter+2, $sumAmount);
              },
        ];
    }
}
