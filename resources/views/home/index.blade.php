@extends('layouts.app')

@section('title')
<title>{{ $home_translation->title }}</title>
@endsection

@section('description')
<meta name="description" content="{{ $home_translation->description }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">{{ __('home.title') }}</h1>
    </div>

    <p>{!! $home_translation->summary !!}</p>
</div>
@endsection
