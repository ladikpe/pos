<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrderDetailsExport implements FromCollection ,  WithHeadingRow
{

    public function collection()
    {

    }


    public function headings(): array
    {

    }
}
