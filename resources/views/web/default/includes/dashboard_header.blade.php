<aside class="dashboard-side">
    <div class="dashboard-brand">
      <div class="dashboard-brand-badge"><img src="{{ $authUser->getAvatar() }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}" width="50" onerror="this.src='https://placehold.co/40x40?text=K'"></div>
      <div>
        <div style="font-weight:900">{{ $authUser->full_name }}</div>
        <div class="dashboard-muted" style="font-size:12px">{{ $authUser->role->caption }}</div>
      </div>
    </div>
    <nav class="dashboard-nav">
        @if($authUser->isAdmin())
            <a href="{{ getAdminPanelUrl() }}" class="active"><span class="dashboard-ms">space_dashboard</span> Dashboard</a>
            <a href="{{ getAdminPanelUrl("/settings") }}"><span class="dashboard-ms">settings</span> Settings</a>
        @else
            <a href="/panel" class="active"><span class="dashboard-ms">space_dashboard</span> Dashboard</a>
            <a href="{{ $authUser->getProfileUrl() }}"><span class="dashboard-ms">person</span> Profile</a>
            <a href="/panel/notifications"><span class="dashboard-ms">notifications</span> Notifications</a>
            <a href="/panel/setting"><span class="dashboard-ms">settings</span> Settings</a>
        @endif
            <a href="/logout"><span class="dashboard-ms">logout</span> Logout</a>
    </nav>
    <div style="margin-top:auto">
      <div class="dashboard-chakra">
        <i style="background:var(--red)"></i><i style="background:var(--orange)"></i><i style="background:var(--yellow)"></i>
        <i style="background:var(--green)"></i><i style="background:var(--blue)"></i><i style="background:var(--indigo)"></i><i style="background:var(--violet)"></i>
      </div>
    </div>
</aside>