<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderServiceRequest extends FormRequest
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
            'feedback_service'  => 'required|min:5|max:1000',
            'feedback_email'    => 'required|min:5|max:1000',
            'feedback_message'  => 'required|min:5|max:1000'
        ];
    }
}
