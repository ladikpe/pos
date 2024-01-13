<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexReportCustomerTypeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'month' => 'nullable|string',
            'year' => 'nullable|string',
            'customer_type_id' => 'nullable|exists:customer_types,id',
            'branch_id' => 'required|exists:branches,id',
            'customer_id' => 'nullable|exists:customers,id'
        ];
    }
}
