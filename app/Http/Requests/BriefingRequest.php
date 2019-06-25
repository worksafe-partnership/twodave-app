<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BriefingRequest extends FormRequest
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
            'vtram_id' => 'required|exists:vtrams,id',
            'briefed_by' => 'required|max:100',
            'name' => 'required|max:100'
        ];
    }

    public function messages()
    {
        return [
            'vtram_id.required' => 'Please select a VTRAM',
            'vtram_id.exists' => 'The selected VTRAM does not exist',
            'briefed_by.required' => 'Please enter who Briefed the VTRAM',
            'briefed_by.max' => 'Briefed By cannot be more than 100 characters',
            'name.required' => 'Please enter the Briefing Name',
            'name.max' => 'The Briefing Name cannot be more than 100 characters'
        ];
    }
}
