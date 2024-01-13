<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' =>  'required|string',
            'email' => 'nullable|email|unique:customers,email',
            'phone_number' => 'required|unique:customers,phone_number',
            'customer_type_id' => 'required|exists:customer_types,id',
            'address' => 'nullable|string',
            'gender' => [ 'nullable',Rule::in(['male', 'female', 'others', 'unknown'] ) ],
            'business_segment_id' => 'required|exists:business_segments,id'
        ];
    }
}
