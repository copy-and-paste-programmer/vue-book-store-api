<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|email:rfc,filter|regex:/(.+)@(.+)\.(.+)/i|unique:users',
            'password' => 'required|min:8|max:50',
            'confirm_password' => 'required|required_with:password|same:password|min:8|max:50',
            'phone_no' => 'required|numeric',
            'address' => 'required|string|max:255'
        ];
    }
}
