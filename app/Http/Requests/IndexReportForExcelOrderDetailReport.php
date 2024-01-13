<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexReportForExcelOrderDetailReport extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string',
            'user_id' => 'nullable|integer',
            'export_to_excel' => [ 'required', Rule::In(['export']), ],
        ];
    }
}
