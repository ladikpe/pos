<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DebtorReportExport implements FromCollection, WithEvents
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
                'ORDER NUMBER' => $item['order_number'],
                'DEBTOR NUMBER' => $item['debtor_number'],
                'TOTAL AMOUNT'  => $item['total_amount'],
                'DATE' => $item['created_at'],
                'CASHIER' => $item['employee']['first_name']  ?? null .' '.$item['employee']['first_name'] ?? null,
                'CUSTOMER' => $item['customer']['name'] ?? null,
            ];
          })
        ]);
    }



    public function registerEvents(): array 
    {
        $counter = $this->data->count();
        $sumTotalAmount = $this->data->sum('total_amount');
    
        return [
            AfterSheet::class => function(AfterSheet $event)
             use ($counter, $sumTotalAmount) {
                $event->sheet->setCellValue('C'.$counter+2, $sumTotalAmount);
              },
        ];
    }


}
