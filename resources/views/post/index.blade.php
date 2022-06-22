@extends('layouts.app')

@section('title')Posts
@endsection

@section('content')
<div class="row">
    @foreach($posts as $post)
    <div class="col-md-3">
        <div class="card">
            <img class="card-img-top" src="{{ $post->image }}">
            <div class="card-body">
                <h5 class="card-title">{{ $post->url }}</h5>
                <p class="card-text">{{ $post->category_id }}</p>
                <p class="card-text">{{ $post->readtima }}</p>
                <p class="card-text">{{ $post->created_at }}</p>
                <a href="{{ route('post.show', ['id' => $post->id]) }}" class="btn btn-primary">{{ $post->url }}</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
