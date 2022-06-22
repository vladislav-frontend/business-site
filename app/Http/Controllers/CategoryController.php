<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Category;
use App\Models\Post;

class CategoryController extends Controller
{
    public function index()
    {
        $current_language = $this->current_language();
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        $category_translations = null;

        foreach ($categories as $category) {
            foreach ($category->translations as $translation) {
                if ($translation->language_id == $current_language) {
                    $category_translations[] = $translation;
                }
            }
        }

        return view('category.index',  ['categories' => $categories, 'category_translations' => $category_translations]);
    }
    
    public function show($category_slug)
    {
        $current_language = $this->current_language();
        $category = Category::where('category_slug', '=', $category_slug)->first();
        if (!$category) abort('404');
        $posts = $category->posts;
        $category_translation = null;
        $posts_translations = null;

        foreach ($category->translations as $category_translation) {
            if ($category_translation->language_id == $current_language) {
                $category_translation = $category_translation;
                break;
            }
        }

        foreach ($posts as $post) {
            foreach ($post->translations as $post_translation) {
                if ($post_translation->language_id == $current_language) {
                    $posts_translations[] = $post_translation;
                }
            }
        }

        return view('category.show', [
            'language'              => $current_language,
            'category'              => $category,
            'category_translation'  => $category_translation,
            'posts'                 => $posts,
            'posts_translations'    => $posts_translations
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
