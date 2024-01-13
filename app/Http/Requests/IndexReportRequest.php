<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexReportRequest extends FormRequest
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
            'user_id' => 'nullable|integer|exists:users,id',
            'limit' => 'nullable|integer',
            'branch_id' => 'required|exists:branches,id',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'get_all_trashed_transactions' => 'nullable|boolean'
        ];
    }
}
