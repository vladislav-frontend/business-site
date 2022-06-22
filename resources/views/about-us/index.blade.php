@extends('layouts.app')

@section('title')
<title>{{ $aboutus_translation->title }}</title>
@endsection

@section('description')
<meta name="description" content="{{ $aboutus_translation->description }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">{{ __('about-us.title') }}</h1>

        {!! $aboutus_translation->summary !!}
    </div>

    <div class="col-md-12 mt-5">
        <h2 class="mb-4">{{ __('about-us.vacations_title') }}</h2>

        <div class="d-flex align-items-start">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @foreach($vacations_translations as $vacation_translation)
                <button class="nav-link @if($loop->index == 0) active @endif" id="v-pills-{{ $vacation_translation->id }}-tab" data-bs-toggle="pill" data-bs-target="#v-pills-{{ $vacation_translation->id }}" type="button" role="tab" aria-controls="v-pills-{{ $vacation_translation->id }}" aria-selected="true">{{ $vacation_translation->name }}</button>
                @endforeach
            </div>
            <div class="tab-content" id="v-pills-tabContent">
                @foreach($vacations_translations as $vacation_translation)
                <div class="tab-pane fade @if($loop->index == 0) show active @endif" id="v-pills-{{ $vacation_translation->id }}" role="tabpanel" aria-labelledby="v-pills-{{ $vacation_translation->id }}-tab">{!! $vacation_translation->summary !!}</div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
