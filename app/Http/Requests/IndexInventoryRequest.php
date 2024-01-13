<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexInventoryRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => 'nullable',
            'category_id' => 'nullable|integer|exists:categories,id|between:1,2'
        ];
    }
}
