<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name' => 'required|max:100',
            'short_name' => 'required|max:100',
            'review_timescale' => 'required',
            'vtrams_name' => 'required|max:100',
            'email' => 'required|max:255|email',
            'phone' => 'required',
            'contact_name' => 'required|max:255',
            'low_risk_character' => 'required|max:1',
            'med_risk_character' => 'required|max:1',
            'high_risk_character' => 'required|max:1',
            'no_risk_character' => 'required|max:1',
            'primary_colour' => 'required|max:7',
            'secondary_colour' => 'required|max:7',
            'accept_label' => 'required|max:20',
            'amend_label' => 'required|max:20',
            'reject_label' => 'required|max:20',
            'logo' => 'mimes:jpg,jpeg,png,bmp,tiff'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a Company Name',
            'contact_name.required' => 'Please enter a Contact Name',
            'contact_name.max' => 'The Contact Name cannot be more than 255 characters',
            'short_name.required' => 'Please enter a Company Short Name',
            'name.max' => 'The Company Name cannot be more than 100 character',
            'short_name.max' => 'The Company Short Name cannot be more than 100 character',
            'review_timescale.required' => 'Please select a Review Timescale',
            'vtrams_name.required' => 'Please enter a VTRAMS Name',
            'vtrams_name.max' => 'The VTRAMS Name cannot be more than 100 characters',
            'email.required' => 'Please enter a Contact Email',
            'email.max' => 'The Contact Email cannot be more than 255 characters',
            'email.email' => 'Please enter a valid Email Address',
            'phone.required' => 'Please enter a Phone Number',
            'low_risk_character.required' => 'Please enter a Low Risk Label',
            'low_risk_character.max' => 'The Low Risk Label cannot be more than 1 character',
            'med_risk_character.required' => 'Please enter a Medium Risk Label',
            'med_risk_character.max' => 'The Medium Risk Label cannot be more than 1 character',
            'high_risk_character.required' => 'Please enter a High Risk Label',
            'high_risk_character.max' => 'The High Risk Label cannot be more than 1 character',
            'no_risk_character.required' => 'Please enter a No Risk Label',
            'no_risk_character.max' => 'The No Risk Label cannot be more than 1 character',
            'primary_colour.required' => 'Please select a Primary Colour',
            'primary_colour.max' => 'The Primary Colour needs to be in valid HEX format i.e.',
            'secondary_colour.required' => 'Please select a Secondary Colour',
            'secondary_colour.max' => 'The Secondary Colour needs to be in valid HEX format i.e.',
            'accept_label.required' => 'Please enter an Accept Label',
            'accept_label.max' => 'The Accept Label cannot be more than 20 characters',
            'amend_label.required' => 'Please enter an Amend Label',
            'amend_label.max' => 'The Amend Label cannot be more than 20 characters',
            'reject_label.required' => 'Please enter a Reject Label',
            'reject_label.max' => 'The Reject Label cannot be more than 20 characters',
            'logo.mimes' => 'Only images are allowed to be uploaded for the Logo'
        ];
    }
}
