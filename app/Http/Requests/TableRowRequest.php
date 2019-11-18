<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableRowRequest extends FormRequest
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
            'col_1' => 'max:255',
            'col_2' => 'max:255',
            'col_3' => 'max:255',
            'col_4' => 'max:255',
            'col_5' => 'max:255',
            'methodology_id' => 'exists:methodologies,id'
        ];
    }

    public function messages()
    {
        return [
            'col_1.max' => 'Column 1 cannot be more than 255 characters',
            'col_2.max' => 'Column 2 cannot be more than 255 characters',
            'col_3.max' => 'Column 3 cannot be more than 255 characters',
            'col_4.max' => 'Column 4 cannot be more than 255 characters',
            'col_5.max' => 'Column 5 cannot be more than 255 characters',
            'methodology_id.exists' => 'The selected Method Statement does not exist'
        ];
    }
}
