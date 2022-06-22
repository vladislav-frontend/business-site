<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Language;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $current_language = $this->current_language();
        $home = Home::first();
        $home_translation = null;

        foreach ($home->translations as $translation) {
            if ($translation->language_id == $current_language) {
                $home_translation = $translation;
            }
        }

        return view('home.index',  ['home' => $home, 'home_translation' => $home_translation]);
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
