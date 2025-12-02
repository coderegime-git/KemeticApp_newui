<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
@endphp

<head>
    @include('web.default.includes.metas')
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- General CSS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">

    <!-- Main App CSS -->
    <link rel="stylesheet" href="/assets/default/css/app.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

</head>

<body class="@if(isset($isMembershipPage) && $isMembershipPage) membership-body @endif @if($isRtl) rtl @endif">

<div id="app" class="app {{ (!empty($floatingBar) and $floatingBar->position == 'top' and $floatingBar->fixed) ? 'has-fixed-top-floating-bar' : '' }}">
    

     @include('web.default.includes.dashboard_header')

      <main class="dashboard-main">
      @yield('content')
    </main>
    
   
</div>

<!-- Template JS File -->
<script src="/assets/default/js/app.js"></script>
<!-- <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script> -->
<!-- <script src="/assets/default/js/parts/forms.min.js"></script> -->
<script src="/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
<script src="/assets/default/vendors/moment.min.js"></script>
<script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>
<script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>

@if(empty($justMobileApp) and checkShowCookieSecurityDialog())
    @include('web.default.includes.cookie-security')
@endif


<script>

  // Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
  const menuToggle = document.querySelector('.mobile-menu-toggle');
  const sidebar = document.querySelector('.dashboard-side');
  const overlay = document.querySelector('.mobile-overlay');
  
  if (menuToggle && sidebar && overlay) {
    menuToggle.addEventListener('click', function() {
      sidebar.classList.toggle('mobile-open');
      overlay.classList.toggle('active');
    });
    
    overlay.addEventListener('click', function() {
      sidebar.classList.remove('mobile-open');
      overlay.classList.remove('active');
    });
  }
  
  // Close menu when clicking on a nav link (optional)
  const navLinks = document.querySelectorAll('.dashboard-nav a');
  navLinks.forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 768) {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
      }
    });
  });
});
        // Role tabs
        const roleTabs = document.querySelectorAll('#dashboard-roleTabs button');
        const grids = {
            seeker: document.getElementById('dashboard-grid-seeker'),
            creator: document.getElementById('dashboard-grid-creator'),
            keeper: document.getElementById('dashboard-grid-keeper'),
        };
        
        roleTabs.forEach(btn => {
            btn.onclick = () => {
                roleTabs.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const r = btn.dataset.role;
                Object.values(grids).forEach(g => g.style.display = 'none');
                grids[r].style.display = '';
            }
        });

        // Drawer helpers
        function sampleTable(title, cols=['ID','Title','Owner','Updated']){
            const rows = Array.from({length:10},(_,i)=>({
                id:(1000+i).toString(16).slice(-6),
                t:`${title} ${i+1}`,
                o:'@kemet.creator',
                u:new Date(Date.now()-Math.random()*86400000*7).toISOString().slice(0,19).replace('T',' ')
            }));
            return `
                <div class="section-title">${title}</div>
                <div style="overflow:auto">
                    <table><thead><tr>${cols.map(c=>`<th>${c}</th>`).join('')}</tr></thead>
                        <tbody>${rows.map(r=>`<tr><td>#${r.id}</td><td>${r.t}</td><td>${r.o}</td><td>${r.u}</td></tr>`).join('')}</tbody>
                    </table>
                </div>
            `;
        }

        function openDrawer(title, key){
            const d = document.getElementById('dashboard-drawer');
            document.getElementById('dashboard-drawerTitle').textContent = title;

            // Simple router by key (replace with live data later)
            const body = document.getElementById('dashboard-drawerBody');
            const map = {
                continue: sampleTable('Continue Learning'),
                myCourses: sampleTable('My Courses'),
                savedReels: sampleTable('Saved Reels', ['ID','Reel','Creator','Saved']),
                orders: sampleTable('Orders', ['ID','Items','Total','Date']),
                membership: sampleTable('Membership', ['Plan','Cycle','Status','Renewal']),
                messages: sampleTable('Messages', ['User','Snippet','When','Status']),

                reelStudio: sampleTable('Reel Studio', ['ID','Title','State','Updated']),
                liveStudio: sampleTable('Livestream Studio', ['ID','Stream','State','Updated']),
                creatorAnalytics: sampleTable('Creator Analytics', ['Metric','Value','Window','Updated']),
                payouts: sampleTable('Payouts', ['ID','Amount','State','Date']),

                courses: sampleTable('Courses', ['ID','Course','Enrollments','Updated']),
                students: sampleTable('Students', ['User','Course','Progress','Updated']),
                products: sampleTable('Products', ['SKU','Product','Stock','Updated']),
                ordersVendor: sampleTable('Vendor Orders', ['ID','Buyer','Total','Date']),
                books: sampleTable('Books', ['ID','Title','Format','Updated']),
                royalties: sampleTable('Royalties', ['Month','Amount','Currency','Status']),
                keeperAnalytics: sampleTable('Analytics', ['Metric','Value','Window','Updated']),
            };
            body.innerHTML = map[key] || sampleTable(title);
            d.classList.add('open');
        }
        
        function closeDrawer(){ 
            document.getElementById('dashboard-drawer').classList.remove('open'); 
        }

        // Bind all tiles (all role grids)
        document.querySelectorAll('.dashboard-role-grid .dashboard-tile').forEach(t => {
            t.addEventListener('click', () => {
                openDrawer(t.querySelector('.dashboard-title').textContent, t.dataset.key || 'items');
            });
        });
    </script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
  
  // Stories: add + view
  const addBtn = document.getElementById('addStory');
  const fileInput = document.getElementById('storyInput');
  const stories = document.getElementById('stories');

  if (addBtn && fileInput) {
    addBtn.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', (e) => {
      const file = e.target.files?.[0]; 
      if(!file) return;
      
      const url = URL.createObjectURL(file);
      const type = file.type.startsWith('video') ? 'video' : 'image';

      const story = document.createElement('div');
      story.className = 'profile-story';
      story.innerHTML = `
        <div class="profile-ring profile-open" data-src="${url}" data-type="${type}">
          <div class="profile-inner">${ type==='video'
            ? `<video src="${url}" muted></video>` 
            : `<img src="${url}" alt="">` }</div>
        </div>
        You
      `;
      stories.insertBefore(story, stories.children[1]);
      bindOpen(story.querySelector('.profile-open'));
      fileInput.value = '';
    });
  }

  // Viewer
  const modal = document.getElementById('modal');
  const mediaBox = document.getElementById('media');
  const bar = document.getElementById('bar');
  const closeBtn = document.getElementById('close');
  let timer = null, progress = 0, duration = 5000;

  function openStory(src, type) {
    if (!mediaBox || !bar || !modal) return;
    
    mediaBox.innerHTML = type === 'video'
      ? `<video src="${src}" autoplay playsinline muted></video>`
      : `<img src="${src}" alt="">`;
    
    progress = 0; 
    bar.style.width = '0%';
    modal.classList.add('open');
    
    if(type === 'video') {
      const v = mediaBox.querySelector('video');
      v.onloadedmetadata = () => {
        duration = Math.min(8000, Math.max(3000, (v.duration || 5) * 1000));
        tick();
      };
    } else {
      duration = 5000; 
      tick();
    }
  }

  function tick() {
    clearInterval(timer);
    const start = performance.now();
    timer = setInterval(() => {
      progress = Math.min(1, (performance.now() - start) / duration);
      if (bar) {
        bar.style.width = (progress * 100).toFixed(2) + '%';
      }
      if(progress >= 1) { 
        clearInterval(timer); 
        if (modal) modal.classList.remove('open'); 
      }
    }, 50);
  }

  function bindOpen(el) {
    if (el) {
      el.addEventListener('click', () => openStory(el.dataset.src, el.dataset.type));
    }
  }

  // Bind existing story elements
  document.querySelectorAll('.profile-ring.profile-open').forEach(bindOpen);

  if (closeBtn && modal) {
    closeBtn.addEventListener('click', () => { 
      clearInterval(timer); 
      modal.classList.remove('open'); 
    });
  }
});
</script> 

@if(session()->has('toast'))
    <script>
        (function () {
            "use strict";
            $.toast({
                heading: '{{ session()->get('toast')['title'] ?? '' }}',
                text: '{{ session()->get('toast')['msg'] ?? '' }}',
                bgColor: '@if(session()->get('toast')['status'] == 'success') #43d477 @else #f63c3c @endif',
                textColor: 'white',
                hideAfter: 10000,
                position: 'bottom-right',
                icon: '{{ session()->get('toast')['status'] }}'
            });
        })(jQuery)
    </script>
@endif


@stack('styles_bottom')
@stack('scripts_bottom')

<script src="/assets/default/js/parts/main.min.js"></script>
<!-- <script src="/assets/default/js/home.js"></script> -->

<script>
    @if(session()->has('registration_package_limited'))
    (function () {
        "use strict";
        handleLimitedAccountModal('{!! session()->get('registration_package_limited') !!}')
    })(jQuery)
    {{ session()->forget('registration_package_limited') }}
    @endif

    {!! !empty(getCustomCssAndJs('js')) ? getCustomCssAndJs('js') : '' !!}
</script>



<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>