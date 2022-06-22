@extends('layouts.app')

@section('title')
<title>{{ $category_translation->name }}</title>
@endsection

@section('content')
<div class="row">
  <h2 class="mb-4">{{ $category_translation->name }}</h2>

  @foreach($posts as $post)
    @foreach($posts_translations as $post_translation)
      @if($post_translation->post_id == $post->id)
      <div class="col-md-6">
        <div class="card bg-dark text-white">
          <img alt="{{ $post_translation->name }}" src="{{ url('/') }}/{{ $post->image }}" class="card-img">
          
          <div class="card-img-overlay">
            <a class="card-description" href="{{ route('post.show', ['category_slug' => $category->category_slug, 'post_slug' => $post->post_slug]) }}">
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
  @endforeach
</div>
@endsection
