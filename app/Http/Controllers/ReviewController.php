<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Review;
use App\Http\Requests\ReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('user_uuid');
    }

    public function store(ReviewRequest $request, $category_slug, $post_slug)
    {
        $category = Category::where('category_slug', '=', $category_slug)->first();
        $post = Post::where('post_slug', '=', $post_slug)->first();
        $user_uuid = $request->session()->get('user_uuid');
        $has_review = Review::query()->where('user_uuid', '=', $user_uuid)->first();

        if ($has_review && $has_review->post_id == $post->id) {
            return redirect()->route('post.show', ['category_slug' => $category->category_slug, 'post_slug' => $post->post_slug])->with('fail', 'Review has already been left.');
        }

        $review = new Review;
        $review->post_id = $post->id;
        $review->user_ip = $request->ip();
        $review->user_uuid = $user_uuid;
        $review->user_name = $request->input('user_name');
        $review->description = $request->input('description');
        $review->rating = $request->input('rating');
        $review->status = 'draft';
        $review->save();

        return redirect()->route('post.show', ['category_slug' => $category->category_slug, 'post_slug' => $post->post_slug])->with('success', 'Review successfully submitted.');
    }
}
