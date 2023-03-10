<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email|email:rfc,filter|regex:/(.+)@(.+)\.(.+)/i|unique:authors',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif',
        ];
    }
}
