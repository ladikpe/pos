<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DeletedDailyReportExport implements FromCollection, WithEvents
{
    use Exportable;
    protected $data;
    protected $header;

    public function __construct($header, $data){
        $this->data = $data;
        $this->header = $header;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $value = ['header' => $this->header, 'data' => $this->data];
        return collect([ $value ['header'], collect($value['data'])->map(function ($item){
            return [
                'ID' => $item['id'] ?? null,
//                'Transaction ID'  => $item['reference_number'] ?? null,
                'ORDER NUMBER' => $item['order_number'] ?? null,
                'CUSTOMER' => $item['customer']['name'] ?? null,
                'AMOUNT' => $item['amount'] ?? null,
                'CASH'  => $item['cash'] ?? null,
                'POS'  => $item['pos'] ?? null,
                'TRANSFER'  => $item['transfer'] ?? null,
                'PAY LATER'  => $item['pay_later'] ?? null,
                'DATE/TIME' => $item['transaction_date'] ?? null,
                'CASHIER' =>  implode(' ', array( ($item['user']['first_name']) ?? null , ($item['user']['last_name']) ?? null)),
            ];})
        ]);
    }

    public function headings(): array
    {
        return ["ID", "ORDER NUMBER", "CUSTOMER", "AMOUNT", "CASH", "POS", "TRANSFER", "PAY LATER", "DATE/TIME", "CASHIER"];
    }

    public function registerEvents(): array
    {
        $counter = count($this->data) ?? null;
        $sumAmount = collect($this->data)->sum('amount') ?? null;
        $sumPos = collect($this->data)->sum('pos') ?? null;
        $sumCash = collect($this->data)->sum('cash') ?? null;
        $sumPayLater = collect($this->data)->sum('pay_later') ?? null;
        $sumTransfer = collect($this->data)->sum('transfer') ?? null;

        return [
            AfterSheet::class => function(AfterSheet $event)
             use ($counter, $sumAmount, $sumPos, $sumCash, $sumPayLater, $sumTransfer)
              {
                $event->sheet->setCellValue('D'.$counter+2, $sumAmount)
                              ->setCellValue('E'.$counter+2, $sumCash)
                              ->setCellValue('F'.$counter+2, $sumPos)
                              ->setCellValue('G'.$counter+2, $sumTransfer)
                              ->setCellValue('H'.$counter+2, $sumPayLater);
              },
        ];
    }
}
