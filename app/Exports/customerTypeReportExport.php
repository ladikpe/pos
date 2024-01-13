<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class customerTypeReportExport implements FromCollection, WithEvents
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
            $average = $item['customer_data']['order_detail_sum_quantity'] != 0 ? ( $item['customer_data']['order_detail_sum_amount'] / $item['customer_data']['order_detail_sum_quantity']) : 0;
            return [
//                "PARTICULAR" => $item['customer_data']['customer']['name'] ?? null,
                "CUSTOMER TYPE" => collect($item['customer_data']['customer'])->get('customer_type')['types'] ?? null,
                'CUSTOMER' => $item['customer_data']['customer']['name'] ?? null,
                'QUANTITY' => $item['customer_data']['order_detail_sum_quantity'] ?? null,
                'TOTAL AMOUNT'  =>  $item['customer_data']['order_detail_sum_amount'],
                'AVERAGE' =>  $average ?? null,
                'MONTH' =>  Carbon::parse($item['customer_data']['order_date'])->format('Y-M'),
            ];
         })
        ]);
    }


  public function registerEvents(): array
    {
        $counter = count($this->data);

        $sumQuantity = $this->data->sum(function($item){
            return $item['customer_data']['order_detail_sum_quantity'];
        });

        $sumAmount = $this->data->sum(function($item){
            return $item['customer_data']['order_detail_sum_amount'] ?? null;
        });

        return [
            AfterSheet::class => function(AfterSheet $event)
             use ($counter, $sumQuantity, $sumAmount)
              {  $event->sheet->setCellValue('C'.$counter+2, $sumQuantity)
                              ->setCellValue('D'.$counter+2, $sumAmount);

              },
        ];
    }
}
