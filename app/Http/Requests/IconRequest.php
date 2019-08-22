<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IconRequest extends FormRequest
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
            'image' => 'required',
            'text' => 'required|max:255',
            'methodology_id' => 'exists:methodologies,id'
        ];
    }

    public function messages()
    {
        return [
            'text.required' => 'Please enter the Text',
            'text.max' => 'The Text cannot be more than 255 characters',
            'methodology_id.exists' => 'The selected Methodology does not exist'
        ];
    }
}
