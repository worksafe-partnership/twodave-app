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
        $data = $this->all();
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
                if (isset($data['image_check'])) {
                    $rules['image'] = 'mimes:jpg,jpeg,png,bmp,tiff';
                } else {
                    $rules['image'] = 'required|mimes:jpg,jpeg,png,bmp,tiff';
                }

                $rules['image_on'] = 'required';
                if ($this->image_on != "undefined") {
                    if ($this->image_on == "BEFOR") {
                        $rules['text_before'] = 'required';
                    } else {
                        $rules['text_after'] = 'required';
                    }
                }
                break;
            case "SIMPLE_TABLE":
            case "COMPLEX_TABLE":
                $rules['row_0__col_1'] = 'required';
                break;
            case "PROCESS":
                $rules['row_0__description'] = 'required';
                break;
            case "ICON":
                $rules['icon_main_heading'] = 'required';
                $rules['icon_list_top_0'] = 'required';
                if (isset($data['icon_list_bottom_0'])) {
                    $rules['icon_sub_heading'] = 'required';
                }
                if ($data['icon_sub_heading'] != "") {
                    $rules['icon_list_bottom_0'] = 'required';
                }
                break;
        }

        return $rules;
    }

    public function messages()
    {
        $category = $this->category;
        $messages = [
            'title.required' => 'Please enter a title',
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
            case "COMPLEX_TABLE":
                $messages['row_0__col_1.required'] = 'Please add rows to your table';
                break;
            case "PROCESS":
                $messages['row_0__description.required'] = 'Please add rows to your table';
                break;
            case "ICON":
                $messages['icon_main_heading.required'] = 'Please enter Main Table heading';
                $messages['icon_list_top_0.required'] = 'Please add rows to your Main table';
                $messages['icon_sub_heading.required'] = 'Please enter Additional Table heading';
                $messages['icon_list_bottom_0.required'] = 'Please add rows to your Additional table';
                break;
        }

        return $messages;
    }
}
