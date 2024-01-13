<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionReportExport implements FromCollection
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
        return collect($value);
    }

}
