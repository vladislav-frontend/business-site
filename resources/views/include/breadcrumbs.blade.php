@if(Route::currentRouteName() == 'category.show')
  @foreach($category->translations as $category_translation)
    @if($category_translation->language_id == $language)
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $category_translation->name }}</li>
      </ol>
    </nav>
    @break
    @endif
  @endforeach
@elseif(Route::currentRouteName() == 'post.show')
  @foreach($post->translations as $translation)
    @if($translation->language_id == $language)
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        @foreach($post->category->translations as $category_translation)
          @if($category_translation->language_id == $language)
          <li class="breadcrumb-item"><a href="{{ route('category.show', ['category_slug' => $post->category->category_slug]) }}">{{ $category_translation->name }}</a></li>
          @break
          @endif
        @endforeach
        <li class="breadcrumb-item active" aria-current="page">{{ $translation->name }}</li>
      </ol>
    </nav>
    @break
    @endif
  @endforeach
@elseif(Route::currentRouteName() == 'categories.index')
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ __('category.categories') }}</li>
    </ol>
  </nav>
@elseif(Route::currentRouteName() == 'about-us.index')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('about-us.title') }}</li>
        </ol>
    </nav>
@endif
