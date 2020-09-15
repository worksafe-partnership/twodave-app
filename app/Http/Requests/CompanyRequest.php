<?php

namespace App\Http\Requests;

use Auth;
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
        $rules = [
            'name' => 'required|max:100',
            'short_name' => 'required|max:100',
            'review_timescale' => 'required',
            'vtrams_name' => 'required|max:100',
            'email' => 'required|max:255|email',
            'phone' => 'required',
            'contact_name' => 'required|max:255',
            'primary_colour' => 'required|max:7',
            'secondary_colour' => 'required|max:7',
            'accept_label' => 'required|max:20',
            'amend_label' => 'required|max:20',
            'reject_label' => 'required|max:20',
            'logo' => 'mimes:jpg,jpeg,png,bmp,tiff',
            'message' => 'max:255',
            'num_vtrams' => 'required_with:sub_frequency,start_date',
            'sub_frequency' => 'required_with:num_vtrams,start_date',
            'start_date' => 'required_with:sub_frequency,num_vtrams',
        ];

        if (strlen($this->start_date) > 0) {
            $rules['start_date'] .= '|before:tomorrow';
        }

        return $rules;
    }

    public function messages()
    {
        $user = Auth::user();
        return [
            'name.required' => 'Please enter a Company Name',
            'contact_name.required' => 'Please enter a Contact Name',
            'contact_name.max' => 'The Contact Name cannot be more than 255 characters',
            'short_name.required' => 'Please enter a Company Short Name',
            'name.max' => 'The Company Name cannot be more than 100 character',
            'short_name.max' => 'The Company Short Name cannot be more than 100 character',
            'review_timescale.required' => 'Please select a Review Timescale',
            'vtrams_name.required' => 'Please enter a '.($user->company->vtrams_name ?? 'VTRAMS').' Name',
            'vtrams_name.max' => 'The '.($user->company->vtrams_name ?? 'VTRAMS').' Name cannot be more than 100 characters',
            'email.required' => 'Please enter a Contact Email',
            'email.max' => 'The Contact Email cannot be more than 255 characters',
            'email.email' => 'Please enter a valid Email Address',
            'phone.required' => 'Please enter a Phone Number',
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
            'logo.mimes' => 'Only images are allowed to be uploaded for the Logo',
            'num_vtrams.required_with' => 'Please fill in all Subscription Information',
            'sub_frequency.required_with' => 'Please fill in all Subscription Information',
            'start_date.required_with' => 'Please fill in all Subscription Information',
            'start_date.before' => 'The subscription must start today or in the past',
        ];
    }
}
