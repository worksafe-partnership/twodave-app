<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MethodologyRequest extends FormRequest
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
            'category' => 'required',
            'image' => 'mimes:jpg,jpeg,png,bmp,tiff'
        ];
    }

    public function messages()
    {
        return [
            'category.required' => 'Please select a Category',
            'image.mimes' => 'Only images are allowed to be uploaded'
        ];
    }
}
