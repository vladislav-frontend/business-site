@extends('layouts.app')

@section('title')
<title>{{ $contacts_translation->title }}</title>
@endsection

@section('description')
<meta name="description" content="{{ $contacts_translation->description }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">{{ __('contacts.title') }}</h1>
    </div>

    <div class="col-md-6">
        <h3 class="mb-4">{{ __('contacts.form') }}</h3>

        @include('include.alerts')

        <form action="{{ route('feedback.send') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <select class="form-select form-select-lg" name="feedback_theme" aria-label="Default select example">
                        <option selected>{{ __('contacts.theme') }}</option>
                        <option value="Develop">Develop</option>
                        <option value="SEO Optimization">SEO Optimization</option>
                        <option value="Design">Design</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control form-control-lg" name="feedback_name" placeholder="{{ __('contacts.name') }}">
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control form-control-lg" name="feedback_email" placeholder="{{ __('contacts.email') }}">
                </div>
                <div class="mb-3">
                    <input type="tel" class="form-control form-control-lg" name="feedback_phone" placeholder="{{ __('contacts.phone') }}">
                </div>
                <div class="mb-3">
                    <textarea class="form-control form-control-lg" name="feedback_message" rows="3" placeholder="{{ __('contacts.message') }}"></textarea>
                </div>
                <button type="submit" class="btn btn-lg btn-primary">{{ __('contacts.send') }}</button>
            </div>
        </form>
    </div>

    <div class="col-md-6 contacts-items">
        <h3 class="mb-4">{{ __('contacts.contacts') }}</h3>

        <div class="row">
            <div class="col-md-6">
                <a href=""><i class="bi bi-telephone-fill"></i> {{ $contacts->phone_1 }}</a>
                <a href=""><i class="bi bi-telegram"></i> {{ $contacts->telegram }}</a>
            </div>
            <div class="col-md-6">
                <a href=""><i class="bi bi-telephone-fill"></i> {{ $contacts->phone_2 }}</a>
                <a href=""><i class="bi bi-envelope"></i> {{ $contacts->email }}</a>
                <a href=""><i class="bi bi-skype"></i> {{ $contacts->skype }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
