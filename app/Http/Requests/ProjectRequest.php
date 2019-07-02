<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'ref' => 'required|max:100',
            'company_id' => 'required|exists:companies,id',
            'project_admin' => 'required|exists:users,id',
            'principle_contractor_name' => 'required_if:principle_contractor,1|max:255',
            'principle_contractor_email' => 'required_if:principle_contractor,1|max:100',
            'client_name' => 'required|max:150',
            'review_timescale' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a Project Name',
            'name.max' => 'The Project Name cannot be more than 100 characters',
            'ref.required' => 'Please enter the Project Reference',
            'ref.max' => 'The Project Reference cannot be more than 100 characters',
            'company_id.required' => 'Please select a Company for this Project',
            'company_id.exists' => 'The selected Company does not exist',
            'project_admin.required' => 'Please select a Project Admin',
            'project_admin.exists' => 'The selected Project Admin does not exist',
            'principle_contractor_name.required_if' => 'Please fill in the Principle Contractor Name',
            'principle_contractor_name.max' => 'The Principle Contractor Name cannot be more than 255 characters',
            'principle_contractor_email.required_if' => 'Please fill in the Principle Contractor Email',
            'principle_contractor_email.max' => 'The Principle Contractor Email cannot be more than 100 characters',
            'client_name.required' => 'Please enter the Client Name',
            'client_name.max' => 'The Client Name cannot be more than 150 characters',
            'review_timescale.required' => 'Please select the Review Timescale'
        ];
    }
}
