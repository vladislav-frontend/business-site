@extends('layouts.app')

@section('title')
<title>{!! $posts_translations->title !!}</title>
@endsection

@section('description')
<meta name="description" content="{!! $posts_translations->description !!}">
@endsection

@section('content')
    <div class="post-body">
        <h1>{{ $posts_translations->name }}</h1>
        <div class="post-meta">
            @if($user)
            <p><i class="bi bi-person"></i> <a href="{{ route('user.show', ['id' => $user->id]) }}">{{ $user->name }}</a></p>
            @endif
            <p><a href="{{ route('category.show', ['category_slug' => $category->category_slug]) }}"><i class="bi bi-bookmark"></i> {{ $category_translation->name }}</a></p>
            <p><i class="bi bi-calendar3"></i> {{ $post->created_at->format('d.m.Y') }}</p>
            <p><i class="bi bi-alarm"></i> {{ $post->readtime }} min</p>
            @if($rating)
            <p><i class="bi bi-star"></i> {{ $rating }}</p>
            @endif
            @if($view_page)
            <p><i class="bi bi-eye"></i> {{ $view_page }}</p>
            @endif
        </div>
        <div class="post-image"><img src="{{ url('/') }}/{{ $post->image }}"></div>
        {!! $posts_translations->content !!}

        <div class="text-center">
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#orderModal">{{ __('modal.order-button') }}</button>
        </div>
    </div>

    @if($post->reviews)
    <div class="row mt-5">
        <div class="col-md-12">
            @foreach($post->reviews as $review)
            @if($review->status == 'published')
            <div class="card">
                <div class="card-body">
                    <h4>{{ $review->user_name }}</h4>
                    <div class="d-flex align-center justify-content-between mb-2">
                        <small>{{ $review->rating }}</small>
                        <small>{{ $review->created_at->format('d.m.Y') }}</small>
                    </div>
                    <p class="card-text">{{ $review->description }}</p>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    @if($review_message)
    <div class="alert alert-warning">
        {{ $review_message }}
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div style="margin: 30px 0 0 0; padding: 30px; background: #eee;">
                @include('include.alerts')

                <h3 class="mb-4">{{ __('reviews.reviews') }}</h3>
                <form method="post" action="{{ route('review.store', ['category_slug' => $category->category_slug, 'post_slug' => $post->post_slug]) }}">
                    @csrf

                    <div class="form-group">
                        <label for="user_name" class="form-label">{{ __('reviews.name') }}</label>
                        <input type="text" name="user_name" class="form-control">
                    </div>

                    <div class="form-group mt-3">
                        <label for="description" class="form-label">{{ __('reviews.message') }}</label>
                        <textarea id="mytextarea" name="description" class="form-control"></textarea>
                    </div>

                    <div class="form-group mt-3 d-flex">
                        <div class="form-check" style="margin-right: 20px;">
                            <input class="form-check-input" type="radio" name="rating" value="1">
                            <label class="form-check-label" for="flexRadioDefault1">1</label>
                        </div>
                        <div class="form-check" style="margin-right: 20px;">
                            <input class="form-check-input" type="radio" name="rating" value="2">
                            <label class="form-check-label" for="flexRadioDefault2">2</label>
                        </div>
                        <div class="form-check" style="margin-right: 20px;">
                            <input class="form-check-input" type="radio" name="rating" value="3">
                            <label class="form-check-label" for="flexRadioDefault3">3</label>
                        </div>
                        <div class="form-check" style="margin-right: 20px;">
                            <input class="form-check-input" type="radio" name="rating" value="4">
                            <label class="form-check-label" for="flexRadioDefault4">4</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rating" value="5">
                            <label class="form-check-label" for="flexRadioDefault5">5</label>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-success">{{ __('reviews.send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
