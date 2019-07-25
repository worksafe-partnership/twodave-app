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
        $category = $this->category;
        $rules = [
            'category' => 'required'
        ];

        switch ($category) {
            case "TEXT":
                $rules['text_before'] = 'required';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        $category = $this->category;
        $messages = [
            'category.required' => 'Please select a Category',
        ];

        switch ($category) {
            case "TEXT":
                $messages['text_before.required'] = 'Please enter content';
                break;
        }

        return $messages;
    }
}
