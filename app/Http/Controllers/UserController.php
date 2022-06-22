<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Translation;
use App\Models\CategoryTranslation;
use App\Models\Language;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::where('id', '=', $id)->first();
        $profile = $user->profile;
        $posts = $user->posts;
        $categories_translations = null;
        $posts_translations = null;

        foreach (Translation::all() as $post_translation) {
            if ($post_translation->language_id == $this->current_language()) {
                $posts_translations[] = $post_translation;
            }
        }

        foreach (CategoryTranslation::all() as $category_translation) {
            if ($category_translation->language_id == $this->current_language()) {
                $categories_translations[] = $category_translation;
            }
        }

        return view('user.show', [
            'user' => $user,
            'profile' => $profile,
            'posts_translations' => $posts_translations,
            'categories_translations' => $categories_translations,
            'posts' => $posts
        ]);
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
