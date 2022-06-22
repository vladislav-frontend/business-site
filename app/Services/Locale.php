<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use App\Models\Language;

class Locale
{

  public function check()
  {
    if (Schema::hasTable('languages')) {
      $language_priority = Language::where('priority', '=', 1)->first();

      if (request()->segment(1, '') == $language_priority->code) {
        $current_url = '/' . request()->path();
        $target_url = '/' . (implode('/', array_diff(explode('/', request()->path()), [app()->getLocale()])));
        return redirect($target_url)->send();
      }
    }
  }

  public function setLocale()
  {
    if (Schema::hasTable('languages')) {
      $locale = request()->segment(1, '');
      $language_priority = Language::where('priority', '=', 1)->first();
      $set_locale = $language_priority;

      foreach (Language::all() as $language) {
        $language_codes[] = $language->code;
      }

      foreach (Language::all() as $language) {
        if ($locale && $locale == $language_priority->code) {
          $set_locale = $locale;
          break;
        } elseif ($locale && $locale == $language->code) {
          $set_locale = $locale;
          break;
        } elseif (!in_array($locale, $language_codes)) {
          $set_locale = $language_priority->code;
          $locale = '';
          break;
        }
      }

      App::setLocale($set_locale);
      return $locale;
    }
  }

  public function changeLocale($language_code)
  {
    if (Schema::hasTable('languages')) {
      $current_path = explode('/', request()->path());

      foreach (Language::all() as $language) {
        $language_codes[] = $language->code;
      }

      in_array($current_path[0], $language_codes) ? $current_path[0] = $language_code : array_unshift($current_path, $language_code);

      $language_link = '/' . implode('/', $current_path);
      return $language_link;
    }
  }
}
