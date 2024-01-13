<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexBusinessTypeReportForExcelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string',
            'business_segment_id' => 'nullable|exists:business_segments,id',
            'branch_id' => 'required|exists:branches,id',
            'export_to_excel' => [ 'required', Rule::In(['export']), ],
        ];
    }
}
