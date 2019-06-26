<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstructionRequest extends FormRequest
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
            'description' => 'required',
            'label' => 'max:5',
            'image' => 'mimes:jpg,jpeg,png,bmp,tiff',
            'methodology_id' => 'exists:methodologies,id'
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Please enter a Description',
            'label.max' => 'The Label cannot be more than 5 characters',
            'image.mimes' => 'Only images are allowed to be uploaded',
            'methodology_id.exists' => 'The selected Methodology does not exist'
        ];
    }
}
