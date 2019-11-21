<?php

namespace App\Http\Requests;

use Auth;
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
            'area' => 'max:100|required_if:show_area,1',
            'vtram_file' => 'nullable|mimes:pdf',
        ];
    }

    public function messages()
    {
        $user = Auth::user();
        return [
            'name.required' => 'Please enter a '.($user->company->vtrams_name ?? 'VTRAMS').' Name',
            'name.max' => 'The '.($user->company->vtrams_name ?? 'VTRAMS').' Name cannot be more than 100 characters',
            'logo.mimes' => 'Only images are allowed to be uploaded for the Logo',
            'reference.required' => 'Please enter a Reference',
            'reference.max' => 'The Reference cannot be more than 100 characters',
            'responsible_person.max' => 'The Responsible Person cannot be more than 100 charcters',
            'responsible_person.required_if' => 'Please enter the Responsible Person',
            'area.max' => 'The Area Name cannot be more than 100 charcters',
            'area.required_if' => 'Please enter the Area Name',
            'vtram_file.mimes' => ($user->company->vtrams_name ?? 'VTRAMS').' uploaded document must be a PDF'
        ];
    }
}
