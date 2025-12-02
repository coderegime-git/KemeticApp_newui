  <button class="header-openBtn" id="openBtn">
  <span>☰</span> Menu
</button>
<aside class="header-menu" id="menu">
  <div class="header-brand">
    <!-- TODO: replace with your actual logo path -->
    @if(!empty($generalSettings['logo']))
      <img src="{{ $generalSettings['logo'] }}" class="header-logo" alt="site logo">
    @endif
    <div class="header-brand-meta">
      <div class="header-appname">Kemetic app</div>
        @if(!empty($authUser))
        <div class="header-user">
          <!-- TODO: replace with your actual profile image path -->
          <img src="{{ $authUser->getAvatar() }}" class="header-avatar" alt="{{ $authUser->full_name }}" onerror="this.src='https://placehold.co/40x40?text=K'">
          <span>{{ $authUser->full_name }}</span>
        </div>
        @else
          <div class="header-user">
            <a href="/login" class="py-5 px-10 mr-10 text-dark-blue font-14">  {{ trans('auth.login') . ' / ' . trans('auth.register') }}</a>
          </div>
        @endif
      </div>
      <!-- <div class="header-close" id="closeBtn" title="Close">&times;</div> -->
    </div>

    <div class="header-grid" style="margin-top:6px">
      @if(!empty($navbarPages) and count($navbarPages))
        @foreach($navbarPages as $navbarPage)
          @php
            // Define title to CSS class mapping
            $titleClasses = [
              'Home' => 'red',
              'Reels' => 'orange',
              'Course' => 'green',
              'Shop' => 'yellow',
              'Book' => 'blue',
              'Articles' => 'violet',
              'Livestreams' => 'indigo',
              'Dashboard' => 'gold'
            ];
            
            // Get the class for this title, default to 'red' if not found
            $tileClass = $titleClasses[$navbarPage['title']] ?? 'red';
          @endphp
          @if($navbarPage['title'] == 'Dashboard')
            @if(!empty($authUser))
              @if($authUser->isAdmin())
                <a href="{{ getAdminPanelUrl() }}">
                    <div class="header-tile {{ $tileClass }}">
                        <div class="header-ico"><img src="{{ url($navbarPage['icons']) }}" alt="icon" width="40"></div>
                        <div class="header-label">{{ $navbarPage['title'] }}</div>
                    </div>
                </a>  
              @else
                <a href="{{ $navbarPage['link'] }}">
                    <div class="header-tile {{ $tileClass }}">
                        <div class="header-ico"><img src="{{ url($navbarPage['icons']) }}" alt="icon" width="40"></div>
                        <div class="header-label">{{ $navbarPage['title'] }}</div>
                    </div>
                </a>
              @endif
            @endif
            @else
            <a href="{{ $navbarPage['link'] }}">
                <div class="header-tile {{ $tileClass }}">
                    <div class="header-ico"><img src="{{ url($navbarPage['icons']) }}" alt="icon" width="40"></div>
                    <div class="header-label">{{ $navbarPage['title'] }}</div>
                </div>
            </a>
          @endif
        @endforeach
      @endif
    </div>
</aside>
  
  <div class="header-backdrop" id="backdrop"> </div>