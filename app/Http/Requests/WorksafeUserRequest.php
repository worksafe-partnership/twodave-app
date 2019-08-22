<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class WorksafeUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email,'.$this->get('id').',id',
            'name'  => 'required',
            'password' => 'nullable|min:8|confirmed',
            'password_confirmation' => 'nullable|min:8',
            'roles' => 'onlyOneRole',
            'signature' => 'mimes:jpg,jpeg,png,bmp,tiff',
        ];
        if (is_null(Auth::user()->company_id)) {
            $rules['company_id'] = 'companyRequired';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'roles.only_one_role' => 'Please check one (only one) of the Roles boxes',
            'company_id.company_required' => 'A Company must be selected',
            'signature.mimes' => 'Only images are allowed to be uploaded for the Signature',
        ];
    }
}
