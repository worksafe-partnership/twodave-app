<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'file_id' => 'required|mimes:jpg,jpeg,png,bmp,tiff,pdf'
        ];
    }

    public function messages()
    {
        return [
            'file_id.required' => 'Please upload an Attendance Document',
            'file_id.mimes' => 'Only images and PDFs are allowed to be uploaded'
        ];
    }
}
