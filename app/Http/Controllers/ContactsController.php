<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use App\Models\Language;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function index()
    {
        $current_language = $this->current_language();
        $contacts = Contacts::first();
        $contacts_translation = null;

        foreach ($contacts->translations as $translation) {
            if ($translation->language_id == $current_language) {
                $contacts_translation = $translation;
            }
        }

        return view('contacts.index',  ['contacts' => $contacts, 'contacts_translation' => $contacts_translation]);
    }

    public function current_language()
    {
        $languages = Language::all();
        $current_language = null;

        foreach ($languages as $language) {
            if ($language->code == app()->getLocale()) {
                $current_language = $language->id;
                return $current_language;
            }
        }
    }
}
