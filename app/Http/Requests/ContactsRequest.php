<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class ContactsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $languages = Language::all();
        $languages_names = null;

        $languages_names["phone_1"] = 'required|max:1000';
        $languages_names["phone_2"] = 'required|max:1000';
        $languages_names["telegram"] = 'required|max:1000';
        $languages_names["email"] = 'required|max:1000';
        $languages_names["skype"] = 'required|max:1000';
        foreach ($languages as $language) {
            $languages_names["$language->name.title"] = 'required|max:55';
            $languages_names["$language->name.description"] = 'required|max:160';
        }

        return $languages_names;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        $languages = Language::all();
        $languages_names = null;

        foreach ($languages as $language) {
            $languages_names["$language->name.title"] = "$language->name title";
            $languages_names["$language->name.description"] = "$language->name description";
        }

        return $languages_names;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
