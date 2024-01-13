<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryOrProductTypeReportForExcelRequest extends FormRequest
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
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'nullable|exists:inventories,category_id'
        ];
    }
}
