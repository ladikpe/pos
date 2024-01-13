<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GasLimitSettingRequest extends FormRequest
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
            'quantity' => 'required|integer',
            'branch_id' => 'required|exists:settings,branch_id'
        ];
    }
}
