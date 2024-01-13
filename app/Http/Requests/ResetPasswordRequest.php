<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'token' => 'required|string',
            'password' => 'min:6|required|string|confirmed',
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ];
    }
}
