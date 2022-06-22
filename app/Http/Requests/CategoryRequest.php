<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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

        foreach ($languages as $language) {
            $languages_names["$language->name.name"] = 'required|max:255';
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
            $languages_names["$language->name.name"] = "$language->name name";
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
