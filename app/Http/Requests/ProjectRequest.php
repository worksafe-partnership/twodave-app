<?php

namespace App\Http\Requests;

use Auth;
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
        $rules = [
            'name' => 'required|max:100',
            'ref' => 'required|max:100',
            'principle_contractor_name' => 'required_if:principle_contractor,1|max:255',
            'project_admin' => 'required|exists:users,id',
            'principle_contractor_email' => 'required_if:principle_contractor,1|max:200|csv',
            'client_name' => 'required|max:150',
            'review_timescale' => 'required',

            // subcontractor stuff
            'add_subcontractor' => 'required_with:company_name, short_name, company_admin_email, company_admin_name, contact_name, email, phone',
            'company_name' => 'required_with:add_subcontractor',
            'short_name' => 'required_with:add_subcontractor',
            'company_admin_email' => 'required_with:add_subcontractor|nullable|email',
            'company_admin_name' => 'required_with:add_subcontractor',
            'contact_name' => 'required_with:add_subcontractor',
            'email' => 'required_with:add_subcontractor|nullable|email',
            'phone' => 'required_with:add_subcontractor',

            // Contractor stuff
            'add_contractor' => 'required_with:company_name_con, short_name_con, company_admin_email_con, company_admin_name_con, contact_name_con, email_con, phone_con',
            'company_name_con' => 'required_with:add_contractor',
            'short_name_con' => 'required_with:add_contractor',
            'company_admin_email_con' => 'required_with:add_contractor|nullable|email',
            'company_admin_name_con' => 'required_with:add_contractor',
            'contact_name_con' => 'required_with:add_contractor',
            'email_con' => 'required_with:add_contractor|nullable|email',
            'phone_con' => 'required_with:add_contractor',

            'contractors' => 'no_conflict_with_subs'
        ];

        $user = Auth::User();

        if (is_null($user->company_id) || !$user->company->is_principal_contractor) {
            unset($rules['project_admin']);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a Project Name',
            'name.max' => 'The Project Name cannot be more than 100 characters',
            'ref.required' => 'Please enter the Project Reference',
            'ref.max' => 'The Project Reference cannot be more than 100 characters',
            'project_admin.required' => 'Please select a Project Admin',
            'project_admin.exists' => 'The selected Project Admin does not exist',
            'principle_contractor_name.required_if' => 'Please fill in the Principal Contractor Name',
            'principle_contractor_name.max' => 'The Principal Contractor Name cannot be more than 255 characters',
            'principle_contractor_email.required_if' => 'Please fill in the Principal Contractor Email',
            'principle_contractor_email.max' => 'The Principal Contractor Email cannot be more than 100 characters',
            'principle_contractor_email.csv' => 'Please ensure all emails entered are valid',
            'client_name.required' => 'Please enter the Client Name',
            'client_name.max' => 'The Client Name cannot be more than 150 characters',
            'review_timescale.required' => 'Please select the Review Timescale',

            // subcontractor
            'add_subcontractor.required_with' => 'You have entered subcontractor details but not selected \'Add a Subcontractor\'',
            'company_name.required_with' => 'Please populate Subcontractor Company Name',
            'short_name.required_with' => 'Please populate Subcontractor Short Name',
            'company_admin_email.required_with' => 'Please populate Subcontractor Admin User\'s Email',
            'company_admin_name.required_with' => 'Please populate Subcontractor Admin User\s Name',
            'contact_name.required_with' => 'Please populate Subcontractor Contact Name',
            'email.required_with' => 'Please populate Subcontractor Contact Email',
            'phone.required_with' => 'Please populate Subcontractor Phone Number',

            // contractor
            'add_contractor.required_with' => 'You have entered contractor details but not selected \'Add a Contractor\'',
            'company_name_con.required_with' => 'Please populate Contractor Company Name',
            'short_name_con.required_with' => 'Please populate Contractor Short Name',
            'company_admin_email_con.required_with' => 'Please populate Contractor Admin User\'s Email',
            'company_admin_name_con.required_with' => 'Please populate Contractor Admin User\s Name',
            'contact_name_con.required_with' => 'Please populate Contractor Contact Name',
            'email_con.required_with' => 'Please populate Contractor Contact Email',
            'phone_con.required_with' => 'Please populate Contractor Phone Number',

            'contractors.no_conflict_with_subs' => 'A company cannot be both a contractor and a sub-contractor on the same project - Please check your inputs'
        ];
    }
}
