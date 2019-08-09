<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VtramRequest extends FormRequest
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
            'logo' => 'mimes:jpg,jpeg,png,bmp,tiff',
            'reference' => 'required|max:100',
            'responsible_person' => 'max:100|required_if:show_responsible_person,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a VTRAMS Name',
            'name.max' => 'The VTRAMS Name cannot be more than 100 characters',
            'logo.mimes' => 'Only images are allowed to be uploaded for the Logo',
            'reference.required' => 'Please enter a Reference',
            'reference.max' => 'The Reference cannot be more than 100 characters',
            'responsible_person.max' => 'The Responsible Person cannot be more than 100 charcters',
            'responsible_person.required_if' => 'Please enter the Responsible Person'
        ];
    }
}
