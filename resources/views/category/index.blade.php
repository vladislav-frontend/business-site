@extends('layouts.app')

@section('title')
<title>Categories</title>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <h2 class="mb-4">{{ __('category.categories') }}</h2>

    @foreach($categories as $category)
    @foreach($category_translations as $category_translation)
    @if($category->id == $category_translation->category_id)
    <div class="list-group">
      <a href="{{ route('category.show', ['category_slug' => $category->category_slug]) }}" class="list-group-item list-group-item-action">{{ $category_translation->name }}</a>
    </div>
    @endif
    @endforeach
    @endforeach
  </div>
</div>
@endsection
