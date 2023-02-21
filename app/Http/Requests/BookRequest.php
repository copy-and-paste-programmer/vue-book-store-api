<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'name' => ['required','max:200'],
            'price' => ['required','numeric','min:1','max:99999999'],
            'description' => ['required','min:10'],
            'author_id' => ['required'],
            'publisher' => ['required','max:200'],
            'image' => [ $this->isMethod('POST') ? 'required': 'nullable','mimes:jpg,png,jpeg', 'max:2000'],
            'categories.*' => ['required'],
            'published_at' => ['required','date_format:Y-m-d']
        ];
    }
}
