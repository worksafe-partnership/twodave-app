<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditVtramRequest extends FormRequest
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
            'coshh_assessment' => 'mimes:jpg,jpeg,png,bmp,tiff,pdf',
            'havs_noise_assessment' => 'mimes:jpg,jpeg,png,bmp,tiff,pdf',
        ];
    }

    public function messages()
    {
        return [
            'coshh_assessment.mimes' => 'COSHH Assessment must be image or PDF',
            'havs_noise_assessment.mimes' => 'HAVS Noise Assessment must be image or PDF',
        ];
    }
}
