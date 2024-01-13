<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexReportForExcelRequest extends FormRequest
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
            'export_to_excel' => [ 'required', Rule::In(['export']), ],
            'customer_id' => 'nullable|integer|exists:customers,id',
            'user_id' => 'nullable|integer|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
        ];
    }
}
