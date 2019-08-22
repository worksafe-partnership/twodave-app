<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovalRequest extends FormRequest
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
            'review_document' => 'mimes:jpg,jpeg,png,bmp,tiff,pdf',
            'type' => 'required',
        ];

        if (isset($this->resubmit_date) && strlen($this->resubmit_date) > 0) {
            $rules['resubmit_date'] = 'after:today';
        }
        if ($this->type != 'A') {
            $rules['comment'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'review_document.mimes' => 'Only images and PDFs are allowed to be uploaded for the Review Document',
            'resubmit_date.after' => 'Please enter a Resubmit Date after today',
            'comment.required' => 'Please enter a Comment',
            'type.required' => 'Please select an Approval Type',
        ];
    }
}
