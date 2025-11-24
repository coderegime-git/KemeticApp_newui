 <div class="wrap row">
    <div class="brand">
      <div class="logo"></div>
      <div>Kemetic App</div>
      <nav class="nav-links">
             <!-- <a href="/classes">{{ trans('livestreams') }}</a>
          <a href="/classes">{{ trans('courses') }}</a>
          <a href="#">{{ trans('reels') }}</a>
          <a href="/products">{{ trans('shop') }}</a>
          <a href="#">{{ trans('television') }}</a>
          <a href="/blog">{{ trans('articles') }}</a> -->
            @if(!empty($navbarPages) and count($navbarPages))
                @foreach($navbarPages as $navbarPage)
                 <a href="{{ $navbarPage['link'] }}">{{ $navbarPage['title'] }}</a>
         
           @endforeach
                    @endif
        </nav>
    </div>
    <div class="pill" role="tablist" aria-label="Currency">
      <button class="active" data-currency="EUR" aria-selected="true">EUR</button>
      <button data-currency="USD" aria-selected="false">USD</button>
    </div>
  </div>