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
            'category' => 'required',
            'title' => 'required'
        ];

        switch ($category) {
            case "TEXT":
                $rules['text_before'] = 'required';
                break;
            case "TEXT_IMAGE":
                $rules['image'] = 'required';
                $rules['image_on'] = 'required';
                if ($this->image_on != "undefined") {
                    if ($this->image_on == "LEFT") {
                        $rules['text_before'] = 'required';
                    } else {
                        $rules['text_after'] = 'required';
                    }
                }
                break;
            case "SIMPLE_TABLE":
                $rules['row_0__col_1'] = 'required';
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
            case "TEXT_IMAGE":
                $messages['image.required'] = 'Please select an image';
                $messages['image_on.required'] = 'Please confirm text location';
                $messages['text_before.required'] = 'Please enter the "Before Text"';
                $messages['text_after.required'] = 'Please enter the "After Text"';
                break;
            case "SIMPLE_TABLE":
                $messages['row_0__col_1.required'] = 'Please add rows to your table';
                break;
        }

        return $messages;
    }
}
