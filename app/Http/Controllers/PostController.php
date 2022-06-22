<?php

namespace App\Http\Controllers;

use App\Services\Breadcrumbs;
use App\Models\Language;
use App\Models\Category;
use App\Models\Post;
use App\Models\Review;
use App\Models\ViewPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Lang;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('user_uuid');
    }

    public function show(Request $request, $category_slug, $post_slug)
    {
        $current_language = $this->current_language();
        $user_uuid = $request->session()->get('user_uuid');
        $post = Post::where('post_slug', '=', $post_slug)->first();
        if (!$post) abort('404');
        $user = $post->user;
        $category = $post->category;
        $category_translation = null;
        $posts_translations = null;

        $view_page = count(ViewPage::all());
        $user_review = $post->reviews->where('user_uuid', '=', $user_uuid)->first();
        $reviews = Review::all();
        $review_rating = $this->setReviewRating($reviews);
        $review_message = $this->outputReviewMessage($post, $user_review);

        $this->setViewPage($post, $user_uuid);

        foreach ($category->translations as $category_translation) {
            if ($category_translation->language_id == $current_language) {
                $category_translation = $category_translation;
                break;
            }
        }

        foreach ($post->translations as $post_translation) {
            if ($post_translation->language_id == $current_language) {
                $posts_translations = $post_translation;
                break;
            }
        }
        
        return view('post.show', [
            'language'              => $current_language,
            'user'                  => $user,
            'category'              => $category,
            'post'                  => $post,
            'category_translation'  => $category_translation,
            'posts_translations'    => $posts_translations,
            'view_page'             => $view_page,
            'rating'                => $review_rating,
            'review_message'        => $review_message
        ]);
    }

    public function setViewPage($post, $user_uuid)
    {
        $has_view_page = ViewPage::query()->where('post_id', '=', $post->id)->where('user_uuid', '=', $user_uuid)->first();

        if (!$has_view_page) {
            $view_page = new ViewPage;
            $view_page->post_id = $post->id;
            $view_page->user_uuid = $user_uuid;
    
            $view_page->save();
        }
    }

    public function outputReviewMessage($post, $user_review)
    {
        if (!$user_review) $review_message = '';
        if ($user_review && $user_review->post_id == $post->id) {
            switch ($user_review->status) {
                case 'draft':
                    $review_message = __('reviews.on_moderation');
                    break;
                case 'published':
                    $review_message = __('reviews.has_review');
                    break;
                case 'trash':
                    $review_message = $user_review->admin_comment;
                    break;
                default:
                    $review_message = '';
                    break;
            }
        }
        
        return $review_message;
    }

    public function setReviewRating($reviews)
    {
        $review_rating = null;

        if (!$reviews) $review_rating = '';
        if ($reviews) {
            foreach ($reviews as $review) {
                $review_rating += $review->rating;
            }
        }

        if ($review_rating) $review_rating = $review_rating/count($reviews);

        return $review_rating;
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
