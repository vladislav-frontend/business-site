@php
use App\Services\Locale;
@endphp

<div class="languages">
    @foreach($languages as $language)
    <a @if(app()->getLocale() == $language->code) style="font-weight: bold;" @endif href="{{ Locale::changeLocale($language->code) }}">{{ $language->code }}</a>
    @if($loop->index + 1 != count($languages)) / @endif
    @endforeach
</div>
