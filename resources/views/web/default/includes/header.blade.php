 <header class="topnav">
      <div class="container nav-row">
        <div class="brand">Kemetic.app</div>
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
        <div class="nav-cta">
          @if(auth()->check())
            <span class="chip">{{ trans('Free') }} • {{ trans('Join') }}</span>
            <a href="/membership"><button class="btn" style="margin-left:10px">€1/mo or €10/yr</button></a>
          @else
            <a href="/register"><span class="chip">{{ trans('Free') }} • {{ trans('Join') }}</span></a>
            <a href="/register"><button class="btn" style="margin-left:10px">€1/mo or €10/yr</button></a>
          @endif
        </div>
      </div>
    </header>