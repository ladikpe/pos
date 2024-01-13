<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class businessSegmentReportExport implements FromCollection, WithEvents
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
            $business_segment = collect($item->customer)->get('business_segment');
            return [
                'BUSINESS SEGMENT' => collect($business_segment)->get('name'),
                'TOTAL AMOUNT'  => $item['amount'],
                'DATE' => $item['transaction_date'],
                'ORDER NUMBER' => $item['orders']['order_number'],
            ];
        })
        ]);
    }


        public function registerEvents(): array 
    {
        $counter = $this->data->count();
        $sumAmount = $this->data->sum('amount');
     
        return [
            AfterSheet::class => function(AfterSheet $event) use ($counter, $sumAmount) {
            
                $event->sheet->setCellValue('B'.$counter+2, $sumAmount);

              },
        ];
    }
}
