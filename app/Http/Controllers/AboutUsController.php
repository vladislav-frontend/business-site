<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\Language;
use App\Models\Vacation;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index()
    {
        $current_language = $this->current_language();
        $aboutus = AboutUs::first();
        $vacations = Vacation::all();
        $aboutus_translation = null;
        $vacations_translations = null;

        foreach ($aboutus->translations as $translation) {
            if ($translation->language_id == $current_language) {
                $aboutus_translation = $translation;
            }
        }

        foreach ($vacations as $vacation) {
            foreach ($vacation->translations as $translation) {
                if ($translation->language_id == $current_language) {
                    $vacations_translations[] = $translation;
                }
            }
        }

        return view('about-us.index',  ['aboutus' => $aboutus, 'aboutus_translation' => $aboutus_translation, 'vacations_translations' => $vacations_translations]);
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
