<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HazardRequest extends FormRequest
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
            'control' => 'required',
//            'risk' => 'required',
            'risk_severity' => 'required|max:4|min:1',
            'risk_probability' => 'required|max:4|min:1',
//            'r_risk' => 'required',
            'r_risk_severity' => 'required|max:4|min:1',
            'r_risk_probability' => 'required|max:4|min:1',
            'at_risk' => 'required',
            'other_at_risk' => 'required_if:at_risk,O',
            'list_order' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Please enter a Description',
            'control.required' => 'Please enter the Control for this Hazard',
            'risk.required' => 'Please select the Hazard\'s Risk',
            'r_risk.required' => 'Please select the Hazard\'s Reduced Risk',
            'at_risk.required' => 'Please select who is at risk',
            'other_at_risk.required_if' => 'Please specify who is at risk',
        ];
    }
}
