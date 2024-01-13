<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
              'email' => 'required|email|exists:users,email'
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'This Email Is Not Valid',
        ];
    }
}
