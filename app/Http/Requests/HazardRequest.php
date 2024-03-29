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
        $rules = [
            'description' => 'required',
            'control' => 'required',
            'risk' => 'required',
            'risk_severity' => 'required|max:4|min:1',
            'risk_probability' => 'required|max:4|min:1',
            'r_risk' => 'required',
            'r_risk_severity' => 'required|max:4|min:1',
            'r_risk_probability' => 'required|max:4|min:1',
            'at_risk' => 'required',
            'list_order' => 'required',
        ];
        if ($this->at_risk["O"] == "1") {
            $rules['other_at_risk'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'description.required' => 'Please enter a Description',
            'control.required' => 'Please enter the Control for this Risk Assessment',
            'risk.required' => 'Please select the Risk Assessment\'s Risk',
            'r_risk.required' => 'Please select the Risk Assessment\'s Reduced Risk',
            'at_risk.required' => 'Please select who is at risk',
            'other_at_risk.required' => 'Please specify who is at risk',
            'r_risk_severity.required' => 'Please select the Risk Severity',
            'r_risk_probability.required' => 'Please select the Risk Probability',

        ];
    }
}
