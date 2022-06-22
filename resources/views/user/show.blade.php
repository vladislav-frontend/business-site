@extends('layouts.app')

@section('title')
<title>{{ $user->name }}</title>
@endsection

@section('description')
<meta name="description" content="{{ $user->name }}">
@endsection

@section('content')
<div class="user-card">
    <div class="user-avatar"><img alt="{{ $user->name }}" src="{{ url('/') }}/{{ $profile->image ?? '' }}"></div>
    <div class="user-name">{{ $user->name }}</div>
    <div class="user-position">{{ $profile->position ?? '' }}</div>
    <div class="user-contacts">{{ $profile->contacts ?? '' }}</div>
    <div class="user-description">{{ $profile->description ?? '' }}</div>
</div>

<h1 class="mt-4 mb-4">{{ __('user.posts') }}</h1>

<div class="row">
@foreach($posts as $post)
    @foreach($posts_translations as $post_translation)
        @if($post->id == $post_translation->post_id)
            @foreach($categories_translations as $category_translation)
                @if($category_translation->category_id == $post->category->id)
                <div class="col-md-6">
                    <div class="card bg-dark text-white">
                    <img alt="{{ $category_translation->name }}" src="{{ url('/') }}/{{ $post->image }}" class="card-img">

                    <div class="card-img-overlay">
                        <a class="card-description" href="{{ route('post.show', ['category_slug' => $post->category->category_slug, 'post_slug' => $post->post_slug]) }}">
                        <p class="card-title">{{ $post_translation->name }}</p>
                        <div class="d-flex align-cent justify-content-between">
                            <span class="card-text">{{ $category_translation->name }}</span>
                            <span class="card-text">{{ $post->created_at->format('d.m.Y') }}</span>
                        </div>
                        <p class="card-text">{!! $post_translation->summary !!}</p>
                        </a>
                    </div>
                    </div>
                </div>
                @endif
            @endforeach
        @endif
    @endforeach
@endforeach
</div>
@endsection
