<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
              'customer_id' => 'required|exists:customers,id',
              'subtotal' => 'required|numeric',
              'total' => 'required|numeric',
              'discount' => 'nullable|numeric',
              'tax' =>  'nullable|numeric',
              'loyalty_discount' => 'nullable|numeric',
             'items' => 'required|array',
             'items.*.id' => 'required|integer|exists:inventories,id',
             'items.*.quantity' => 'required|integer',
             'items.*.price' => 'required|numeric',
             'payment_break_down' => 'required',
             'payment_break_down.*.transaction_mode_id' => 'required|integer|exists:transaction_modes,id',
             'payment_break_down.*.amount' => 'required|numeric',
             'payment_break_down.*.payment_type_name' => 'nullable|string',
             'payment_break_down.*.description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'payment_break_down.required' => 'Payment Method is Required',
            'payment_break_down.*.transaction_mode_id.required' => 'Transaction Mode is Required',
            'payment_break_down.*.amount.required' => 'The Amount is Required',
            'payment_break_down.*.transaction_mode_id.exists' => 'Payment Method Selected Doesnt Exist',
        ];
    }
}
