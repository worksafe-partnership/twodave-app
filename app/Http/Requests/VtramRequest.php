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
            'description' => 'required',
            'logo' => 'mimes:jpg,jpeg,png,bmp,tiff',
            'reference' => 'required|max:100',
            'key_points' => 'required',
            'havs_noise_assessment' => 'mimes:jpg,jpeg,png,bmp,tiff,pdf',
            'coshh_assessment' => 'mimes:jpg,jpeg,png,bmp,tiff,pdf',
            'responsible_person' => 'max:100|required_if:show_responsible_person,true'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a VTRAM Name',
            'name.max' => 'The VTRAM Name cannot be more than 100 characters',
            'description.required' => 'Please enter a Description',
            'logo.mimes' => 'Only images are allowed to be uploaded for the Logo',
            'reference.required' => 'Please enter a Reference',
            'reference.max' => 'The Reference cannot be more than 100 characters',
            'key_points.required' => 'Please enter the Key Points',
            'havs_noise_assessment.mimes' => 'Only images and PDFS are allowed to be uploaded for the HAVS/Noise Assessment',
            'coshh_assessment.mimes' => 'Only images and PDFS are allowed to be uploaded for the COSHH Assessment',
            'responsible_person.max' => 'The Responsible Person cannot be more than 100 charcters',
            'responsible_person.required_if' => 'Please enter the Responsible Person'
        ];
    }
}
