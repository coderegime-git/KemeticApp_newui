@extends('web.default.layouts.newapp')

@section('content')

  <div class="dashboard-topbar">
    <div class="dashboard-pill">Welcome • @mythoughtsomeverything</div>
    <div class="dashboard-pill" style="color:var(--gold);border-color:var(--gold)">Live</div>
    <div class="dashboard-role" id="dashboard-roleTabs">
      <button class="active" data-role="seeker">Seeker</button>
      <button data-role="creator">Content Creator</button>
      <button data-role="keeper">Wisdom Keeper</button>
    </div>
  </div>
  
  <div class="dashboard-wrap">
    <!-- SEEKER GRID -->
    <section class="dashboard-grid dashboard-role-grid" id="dashboard-grid-seeker">
      <article class="dashboard-tile" style="--tint:var(--blue);--glow:#1E88E544" data-key="continue">
        <div class="dashboard-count">3</div><div class="dashboard-icon"><span class="dashboard-ms">play_arrow</span></div>
        <div class="dashboard-title">Continue Learning</div><div class="dashboard-meta">Resume course lessons</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--green);--glow:#43A04744" data-key="myCourses">
        <div class="dashboard-count">7</div><div class="dashboard-icon"><span class="dashboard-ms">school</span></div>
        <div class="dashboard-title">My Courses</div><div class="dashboard-meta">Owned & enrolled</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--red);--glow:#E5393544" data-key="savedReels">
        <div class="dashboard-count">54</div><div class="dashboard-icon"><span class="dashboard-ms">favorite</span></div>
        <div class="dashboard-title">Saved Reels</div><div class="dashboard-meta">Likes & bookmarks</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--violet);--glow:#8E24AA44" data-key="orders">
        <div class="dashboard-count">12</div><div class="dashboard-icon"><span class="dashboard-ms">shopping_bag</span></div>
        <div class="dashboard-title">Orders</div><div class="dashboard-meta">Shop purchases</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--gold);--glow:#D7B45E44" data-key="membership">
        <div class="dashboard-count">Active</div><div class="dashboard-icon"><span class="dashboard-ms">workspace_premium</span></div>
        <div class="dashboard-title">Membership</div><div class="dashboard-meta">€1/mo</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--indigo);--glow:#5E35B144" data-key="messages">
        <div class="dashboard-count">2</div><div class="dashboard-icon"><span class="dashboard-ms">chat</span></div>
        <div class="dashboard-title">Messages</div><div class="dashboard-meta">DMs & groups</div>
      </article>
    </section>

    <!-- CREATOR GRID -->
    <section class="dashboard-grid dashboard-role-grid" id="dashboard-grid-creator" style="display:none">
      <article class="dashboard-tile" style="--tint:var(--red);--glow:#E5393544" data-key="reelStudio">
        <div class="dashboard-count">+ New</div><div class="dashboard-icon"><span class="dashboard-ms">movie_edit</span></div>
        <div class="dashboard-title">Reel Studio</div><div class="dashboard-meta">Upload & schedule</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--blue);--glow:#1E88E544" data-key="liveStudio">
        <div class="dashboard-count">Go Live</div><div class="dashboard-icon"><span class="dashboard-ms">live_tv</span></div>
        <div class="dashboard-title">Livestream Studio</div><div class="dashboard-meta">RTMP / chat</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--green);--glow:#43A04744" data-key="creatorAnalytics">
        <div class="dashboard-count">1.2M</div><div class="dashboard-icon"><span class="dashboard-ms">analytics</span></div>
        <div class="dashboard-title">Creator Analytics</div><div class="dashboard-meta">Views • watch time</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--gold);--glow:#D7B45E44" data-key="payouts">
        <div class="dashboard-count">€2.8k</div><div class="dashboard-icon"><span class="dashboard-ms">attach_money</span></div>
        <div class="dashboard-title">Payouts</div><div class="dashboard-meta">Balance & history</div>
      </article>
    </section>

    <!-- WISDOM KEEPER GRID (includes Creator + Instructor + Vendor + Books) -->
    <section class="dashboard-grid dashboard-role-grid" id="dashboard-grid-keeper" style="display:none">
      <!-- Creator -->
      <article class="dashboard-tile" style="--tint:var(--red);--glow:#E5393544" data-key="reelStudio">
        <div class="dashboard-count">+ New</div><div class="dashboard-icon"><span class="dashboard-ms">movie_edit</span></div>
        <div class="dashboard-title">Creator • Reel Studio</div><div class="dashboard-meta">Upload & schedule</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--blue);--glow:#1E88E544" data-key="liveStudio">
        <div class="dashboard-count">Ready</div><div class="dashboard-icon"><span class="dashboard-ms">live_tv</span></div>
        <div class="dashboard-title">Creator • Livestream</div><div class="dashboard-meta">RTMP / chat</div>
      </article>

      <!-- Instructor -->
      <article class="dashboard-tile" style="--tint:var(--green);--glow:#43A04744" data-key="courses">
        <div class="dashboard-count">23</div><div class="dashboard-icon"><span class="dashboard-ms">school</span></div>
        <div class="dashboard-title">Instructor • Courses</div><div class="dashboard-meta">Build curriculum</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--indigo);--glow:#5E35B144" data-key="students">
        <div class="dashboard-count">1,540</div><div class="dashboard-icon"><span class="dashboard-ms">groups</span></div>
        <div class="dashboard-title">Instructor • Students</div><div class="dashboard-meta">Progress & Q&A</div>
      </article>

      <!-- Vendor -->
      <article class="dashboard-tile" style="--tint:var(--violet);--glow:#8E24AA44" data-key="products">
        <div class="dashboard-count">148</div><div class="dashboard-icon"><span class="dashboard-ms">storefront</span></div>
        <div class="dashboard-title">Vendor • Products</div><div class="dashboard-meta">Manage catalog</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--yellow);--glow:#FFD60044" data-key="ordersVendor">
        <div class="dashboard-count">7,402</div><div class="dashboard-icon"><span class="dashboard-ms">shopping_bag</span></div>
        <div class="dashboard-title">Vendor • Orders</div><div class="dashboard-meta">Fulfillment</div>
      </article>

      <!-- Books -->
      <article class="dashboard-tile" style="--tint:var(--orange);--glow:#FB8C0044" data-key="books">
        <div class="dashboard-count">36</div><div class="dashboard-icon"><span class="dashboard-ms">menu_book</span></div>
        <div class="dashboard-title">Books • Library</div><div class="dashboard-meta">eBook / Audio</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--gold);--glow:#D7B45E44" data-key="royalties">
        <div class="dashboard-count">€12.4k</div><div class="dashboard-icon"><span class="dashboard-ms">payments</span></div>
        <div class="dashboard-title">Books • Royalties</div><div class="dashboard-meta">Monthly payouts</div>
      </article>

      <!-- Shared -->
      <article class="dashboard-tile" style="--tint:var(--green);--glow:#43A04744" data-key="keeperAnalytics">
        <div class="dashboard-count">↑ 18%</div><div class="dashboard-icon"><span class="dashboard-ms">monitoring</span></div>
        <div class="dashboard-title">Analytics</div><div class="dashboard-meta">Courses • Reels • Shop</div>
      </article>
      <article class="dashboard-tile" style="--tint:var(--gold);--glow:#D7B45E44" data-key="payouts">
        <div class="dashboard-count">€5.1k</div><div class="dashboard-icon"><span class="dashboard-ms">attach_money</span></div>
        <div class="dashboard-title">Payouts</div><div class="dashboard-meta">Balance & history</div>
      </article>
    </section>
  </div>

<!-- Drawer -->
<aside class="dashboard-drawer" id="dashboard-drawer">
  <div class="dashboard-head">
    <div class="dashboard-brand-badge" style="width:40px;height:40px">KA</div>
    <div>
      <div id="dashboard-drawerTitle" style="font-weight:900">Details</div>
      <div class="dashboard-muted" style="font-size:12px">Role module</div>
    </div>
    <button class="dashboard-close" onclick="closeDrawer()">Close</button>
  </div>
  <div class="dashboard-body" id="dashboard-drawerBody"></div>
</aside>
@endsection