@extends('web.default.layouts.newapp')
<style>
  .dashboard-cancel-btn {
  display: inline-block;
  padding: 4px 14px;
  background: transparent;
  border: 1px solid var(--red, #E53935);
  color: var(--red, #E53935);
  border-radius: 20px;
  /* font-size: 12px; */
  font-weight: 600;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
  cursor: pointer;
  float: right;
}
.dashboard-cancel-btn:hover {
  background: var(--red, #E53935);
  color: #fff;
}
</style>
@section('content')

  <div class="dashboard-topbar">
    <div class="dashboard-pill">Welcome • @ {{ $username }}</div>
    <div class="dashboard-pill" style="color:var(--gold);border-color:var(--gold)">Live</div>
    <div class="dashboard-role" id="dashboard-roleTabs">
      @if(isset($seeker_data))
      <button class="active" data-role="seeker" style="cursor: none;">Seeker</button>
      @endif
      @if(isset($creator_data))
      <button data-role="creator">Content Creator</button>
      @endif
      @if(isset($keeper_data))
      <button data-role="keeper">Wisdom Keeper</button>
      @endif
    </div>
  </div>
  
  <div class="dashboard-wrap">
    @if(isset($seeker_data))
      <section class="dashboard-grid dashboard-role-grid" id="dashboard-grid-seeker">
        <article class="dashboard-tile" style="--tint:var(--blue);--glow:#1E88E544" data-key="continue">
          <div class="dashboard-count">{{ $seeker_data['continue_learning'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">play_arrow</span></div>
          <div class="dashboard-title">Continue Learning</div>
          <div class="dashboard-meta">Resume course lessons</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--green);--glow:#43A04744" data-key="myCourses">
          <div class="dashboard-count">{{ $seeker_data['my_courses'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">school</span></div>
          <div class="dashboard-title">My Courses</div>
          <div class="dashboard-meta">Owned & enrolled</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--red);--glow:#E5393544" data-key="savedReels">
          <div class="dashboard-count">{{ $seeker_data['saved_reels'] }}  </div>
          <div class="dashboard-icon"><span class="dashboard-ms">favorite</span></div>
          <div class="dashboard-title">Saved Reels</div>
          <div class="dashboard-meta">Likes & bookmarks</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--violet);--glow:#8E24AA44" data-key="orders">
          <div class="dashboard-count">{{ $seeker_data['orders'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">shopping_bag</span></div>
          <div class="dashboard-title">Orders</div>
          <div class="dashboard-meta">Shop purchases</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--gold);--glow:#D7B45E44" data-key="membership">
          <div class="dashboard-count">{{ $seeker_data['membership']['status'] }} </div>
          <div class="dashboard-icon"><span class="dashboard-ms">workspace_premium</span></div>
          <div class="dashboard-title">Membership</div>
          <div class="dashboard-meta">{{ $seeker_data['membership']['price'] }}</div>
         
          @if($seeker_data['membership']['status'] == 'Active')<a href="/membership" class="dashboard-cancel-btn" onclick="event.stopPropagation()">Cancel</a>@endif
        </article>
        <article class="dashboard-tile" style="--tint:var(--indigo);--glow:#5E35B144" data-key="messages">
          <div class="dashboard-count">{{ $seeker_data['messages'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">chat</span></div>
          <div class="dashboard-title">Messages</div>
          <div class="dashboard-meta">DMs & groups</div>
        </article>
      </section>
    @endif
    
    @if(isset($creator_data))
    
      <section class="dashboard-grid dashboard-role-grid" id="dashboard-grid-creator" style="display:none">
        <article class="dashboard-tile" style="--tint:var(--red);--glow:#E5393544" data-key="reelStudio">
          <div class="dashboard-count">{{ $creator_data['reel_studio'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">movie_edit</span></div>
          <div class="dashboard-title">Reel Studio</div>
          <div class="dashboard-meta">Upload & schedule</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--blue);--glow:#1E88E544" data-key="liveStudio">
          <div class="dashboard-count">{{ $creator_data['live_studio'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">live_tv</span></div>
          <div class="dashboard-title">Livestream Studio</div>
          <div class="dashboard-meta">RTMP / chat</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--green);--glow:#43A04744" data-key="creatorAnalytics">
          <div class="dashboard-count">{{ $creator_data['creator_analytics'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">analytics</span></div>
          <div class="dashboard-title">Creator Analytics</div>
          <div class="dashboard-meta">Views • watch time</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--gold);--glow:#D7B45E44" data-key="payouts">
          <div class="dashboard-count">{{ $creator_data['payouts'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">attach_money</span></div>
          <div class="dashboard-title">Payouts</div>
          <div class="dashboard-meta">Balance & history</div>
        </article>
      </section>
    @endif

    @if(isset($keeper_data))
      <section class="dashboard-grid dashboard-role-grid" id="dashboard-grid-keeper" style="display:none">
        <!-- Creator -->
        <article class="dashboard-tile" style="--tint:var(--red);--glow:#E5393544" data-key="reelStudio">
          <div class="dashboard-count">{{ $keeper_data['reel_studio'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">movie_edit</span></div>
          <div class="dashboard-title">Creator • Reel Studio</div>
          <div class="dashboard-meta">Upload & schedule</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--blue);--glow:#1E88E544" data-key="liveStudio">
          <div class="dashboard-count">{{ $keeper_data['live_studio'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">live_tv</span></div>
          <div class="dashboard-title">Creator • Livestream</div>
          <div class="dashboard-meta">RTMP / chat</div>
        </article>

        <!-- Instructor -->
        <article class="dashboard-tile" style="--tint:var(--green);--glow:#43A04744" data-key="courses">
          <div class="dashboard-count">{{ $keeper_data['courses'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">school</span></div>
          <div class="dashboard-title">Instructor • Courses</div>
          <div class="dashboard-meta">Build curriculum</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--indigo);--glow:#5E35B144" data-key="students">
          <div class="dashboard-count">{{ $keeper_data['students'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">groups</span></div>
          <div class="dashboard-title">Instructor • Students</div>
          <div class="dashboard-meta">Progress & Q&A</div>
        </article>

        <!-- Vendor -->
        <article class="dashboard-tile" style="--tint:var(--violet);--glow:#8E24AA44" data-key="products">
          <div class="dashboard-count">{{ $keeper_data['products'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">storefront</span></div>
          <div class="dashboard-title">Vendor • Products</div>
          <div class="dashboard-meta">Manage catalog</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--yellow);--glow:#FFD60044" data-key="ordersVendor">
          <div class="dashboard-count">{{ $keeper_data['vendor_orders'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">shopping_bag</span></div>
          <div class="dashboard-title">Vendor • Orders</div>
          <div class="dashboard-meta">Fulfillment</div>
        </article>

        <!-- Books -->
        <article class="dashboard-tile" style="--tint:var(--orange);--glow:#FB8C0044" data-key="books">
          <div class="dashboard-count">{{ $keeper_data['books'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">menu_book</span></div>
          <div class="dashboard-title">Books • Library</div>
          <div class="dashboard-meta">eBook / Audio</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--gold);--glow:#D7B45E44" data-key="royalties">
          <div class="dashboard-count">{{ $keeper_data['royalties'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">payments</span></div>
          <div class="dashboard-title">Books • Royalties</div>
          <div class="dashboard-meta">Monthly payouts</div>
        </article>

        <!-- Shared -->
        <article class="dashboard-tile" style="--tint:var(--green);--glow:#43A04744" data-key="keeperAnalytics">
          <div class="dashboard-count">{{ $keeper_data['analytics'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">monitoring</span></div>
          <div class="dashboard-title">Analytics</div>
          <div class="dashboard-meta">Courses • Reels • Shop</div>
        </article>
        <article class="dashboard-tile" style="--tint:var(--gold);--glow:#D7B45E44" data-key="payouts">
          <div class="dashboard-count">{{ $keeper_data['payouts'] }}</div>
          <div class="dashboard-icon"><span class="dashboard-ms">attach_money</span></div>
          <div class="dashboard-title">Payouts</div>
          <div class="dashboard-meta">Balance & history</div>
        </article>

         <article class="dashboard-tile" style="--tint:var(--gold);--glow:#D7B45E44" data-key="membership">
          <div class="dashboard-count">{{ $keeper_data['membership']['status'] }} </div>
          <div class="dashboard-icon"><span class="dashboard-ms">workspace_premium</span></div>
          <div class="dashboard-title">Membership</div>
          <div class="dashboard-meta">{{ $keeper_data['membership']['price'] }}</div>
          @if($keeper_data['membership']['status'] == 'Active')<a href="/membership" class="dashboard-cancel-btn" onclick="event.stopPropagation()">Cancel</a>@endif
        </article>
      </section>
    @endif
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

<!-- Overlay for drawer -->
<div class="dashboard-overlay" onclick="closeDrawer()"></div>

@endsection


<script>
// ===== PASS PHP DATA TO JAVASCRIPT =====
window.seekerData = @json($seeker_data['detailed_data'] ?? []);
window.creatorData = @json($creator_data['detailed_data'] ?? []);
window.keeperData = @json($keeper_data['detailed_data'] ?? []);
window.userRole = '{{ $user_role ?? "seeker" }}';

// ===== DASHBOARD JAVASCRIPT =====
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard loaded. User role:', window.userRole);
    
    // ===== INITIALIZE VARIABLES =====
    const roleTabs = document.querySelectorAll('#dashboard-roleTabs button');
    const grids = {
        seeker: document.getElementById('dashboard-grid-seeker'),
        creator: document.getElementById('dashboard-grid-creator'),
        keeper: document.getElementById('dashboard-grid-keeper')
    };
    
    // ===== REMOVE ALL ACTIVE CLASSES FIRST =====
    roleTabs.forEach(tab => tab.classList.remove('active'));
    
    // ===== HIDE ALL GRIDS FIRST =====
    Object.values(grids).forEach(grid => {
        if (grid) grid.style.display = 'none';
    });
    
    // ===== FUNCTION TO SWITCH ROLE =====
    function switchRole(role) {
        console.log('Switching to role:', role);
        
        // Update tabs
        roleTabs.forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.role === role) {
                tab.classList.add('active');
            }
        });
        
        // Update grids
        Object.entries(grids).forEach(([gridRole, grid]) => {
            if (grid) {
                grid.style.display = gridRole === role ? '' : 'none';
            }
        });
        
        // Store active role in session storage
        sessionStorage.setItem('activeDashboardRole', role);
    }
    
    // ===== ADD CLICK LISTENERS TO ROLE TABS =====
    roleTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const role = this.dataset.role;
            switchRole(role);
        });
    });
    
    // ===== INITIALIZE WITH USER ROLE =====
    function initializeDashboard() {
        // Check session storage first (if user switched tabs before)
        const savedRole = sessionStorage.getItem('activeDashboardRole');
        const defaultRole = savedRole || window.userRole;
        
        // Check if the role tab exists
        const roleTabExists = Array.from(roleTabs).some(tab => tab.dataset.role === defaultRole);
        
        // Determine which role to show
        let activeRole = 'seeker';
        if (roleTabExists) {
            activeRole = defaultRole;
        } else if (roleTabs.length > 0) {
            // Fallback to first available tab
            activeRole = roleTabs[0].dataset.role;
        }
        
        console.log('Initializing dashboard with role:', activeRole);
        switchRole(activeRole);
    }
    
    // ===== INITIALIZE THE DASHBOARD =====
    setTimeout(initializeDashboard, 50);
    
    // ===== DRAWER FUNCTIONALITY =====
    function openDrawer(title, key) {
        console.log('Opening drawer:', title, key);
        
        const drawer = document.getElementById('dashboard-drawer');
        const drawerTitle = document.getElementById('dashboard-drawerTitle');
        const drawerBody = document.getElementById('dashboard-drawerBody');
        
        if (!drawer || !drawerTitle || !drawerBody) {
            console.error('Drawer elements not found');
            return;
        }
        
        // Set title
        drawerTitle.textContent = title;
        
        // Show loading state
        drawerBody.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Loading ${title}...</p>
            </div>
        `;
        
        // Determine which data source to use based on active role
        const activeTab = document.querySelector('#dashboard-roleTabs button.active');
        const activeRole = activeTab ? activeTab.dataset.role : 'seeker';
        
        console.log('Active role for data:', activeRole);
        
        // Get data based on active role
        let data = [];
        switch(activeRole) {
            case 'seeker':
                data = window.seekerData[key] || [];
                break;
            case 'creator':
                data = window.creatorData[key] || [];
                break;
            case 'keeper':
                data = window.keeperData[key] || [];
                break;
        }
        
        console.log('Data for', key, ':', data);
        
        // Render the data after a short delay (for UX)
        setTimeout(() => {
            renderDataTable(drawerBody, data, title, key);
        }, 300);
        
        // Open drawer
        drawer.classList.add('open');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
    
    function closeDrawer() {
        const drawer = document.getElementById('dashboard-drawer');
        if (drawer) {
            drawer.classList.remove('open');
            document.body.style.overflow = ''; // Restore scrolling
        }
    }
    
    function renderDataTable(container, data, title, type) {
        if (!data || data.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;">📭</div>
                    <p>No ${title.toLowerCase()} found</p>
                    <p style="font-size: 14px; opacity: 0.7;">Start exploring to see content here</p>
                </div>
            `;
            return;
        }
        
        // ===== DEFINE COLUMN CONFIGURATIONS =====
        const columnConfigs = {
            'continue': [
                { key: 'title', label: 'Course', width: '35%' },
                { key: 'instructor', label: 'Instructor', width: '25%' },
                { key: 'progress', label: 'Progress', width: '15%', 
                  format: (val) => `<div class="progress-bar">
                      <div class="progress-fill" style="width: ${val}%"></div>
                      <span>${val}%</span>
                    </div>` 
                },
                { key: 'updated_at', label: 'Updated', width: '15%' },
                { key: 'action', label: '', width: '10%',
                  format: (val, row) => row.url ? 
                    `<a href="${row.url}" class="btn-continue">Continue</a>` : '' 
                }
            ],
            'myCourses': [
                { key: 'title', label: 'Course', width: '30%' },
                { key: 'instructor', label: 'Instructor', width: '20%' },
                { key: 'type', label: 'Type', width: '15%' },
                { key: 'progress', label: 'Progress', width: '15%',
                  format: (val) => `${val}%`
                },
                { key: 'enrolled_at', label: 'Enrolled', width: '15%' },
                { key: 'action', label: '', width: '5%',
                  format: (val, row) => row.url ? 
                    `<a href="${row.url}" class="btn-view">View</a>` : '' 
                }
            ],
            'savedReels': [
                { key: 'title', label: 'Reel', width: '35%' },
                { key: 'creator', label: 'Creator', width: '25%' },
                { key: 'views', label: 'Views', width: '15%',
                  format: (val) => val.toLocaleString()
                },
                { key: 'duration', label: 'Duration', width: '10%' },
                { key: 'saved_at', label: 'Saved', width: '15%' }
            ],
            'orders': [
                { key: 'order_id', label: 'Order #', width: '15%' },
                { key: 'items', label: 'Items', width: '35%' },
                { key: 'total', label: 'Total', width: '15%' },
                { key: 'date', label: 'Date', width: '20%' },
                { key: 'status', label: 'Status', width: '15%',
                  format: (val) => `<span class="status-badge status-${val.toLowerCase()}">${val}</span>`
                }
            ],
            'membership': [
                { key: 'plan_description', label: 'Plan', width: '25%' },
                { key: 'cycle', label: 'Cycle', width: '20%' },
                { key: 'days', label: 'Days', width: '20%' },
                { key: 'price', label: 'Price', width: '15%' },
                { key: 'status', label: 'Status', width: '20%',
                  format: (val) => `<span class="status-badge status-active">${val}</span>`
                }
            ],
            'courses': [
                { key: 'title', label: 'Course', width: '30%' },
                { key: 'type', label: 'Type', width: '15%' },
                { key: 'price', label: 'Price', width: '15%' },
                { key: 'enrollments', label: 'Students', width: '15%',
                  format: (val) => val.toLocaleString()
                },
                { key: 'rating', label: 'Rating', width: '15%',
                  format: (val) => `${val}/5 ⭐`
                },
                { key: 'updated_at', label: 'Updated', width: '10%' }
            ],
            'students': [
                { key: 'name', label: 'Student', width: '25%' },
                { key: 'email', label: 'Email', width: '25%' },
                { key: 'course_title', label: 'Course', width: '20%' },
                { key: 'progress', label: 'Progress', width: '15%',
                  format: (val) => `${val}%`
                },
                { key: 'enrolled_at', label: 'Enrolled', width: '15%' }
            ],
            'reelStudio': [
                { key: 'title', label: 'Reel', width: '40%' },
                { key: 'status', label: 'Status', width: '20%' },
                { key: 'views', label: 'Views', width: '20%' },
                { key: 'created_at', label: 'Created', width: '20%' }
            ],
            'liveStudio': [
                { key: 'title', label: 'Stream', width: '40%' },
                { key: 'status', label: 'Status', width: '20%' },
                { key: 'viewers', label: 'Viewers', width: '20%' },
                { key: 'scheduled', label: 'Scheduled', width: '20%' }
            ],
            'creatorAnalytics': [
                { key: 'metric', label: 'Metric', width: '40%' },
                { key: 'value', label: 'Value', width: '30%' },
                { key: 'change', label: 'Change', width: '30%' }
            ],
            'payouts': [
                { key: 'date', label: 'Date', width: '25%' },
                { key: 'amount', label: 'Amount', width: '25%' },
                { key: 'status', label: 'Status', width: '25%' },
                { key: 'method', label: 'Method', width: '25%' }
            ],
            'products': [
                { key: 'name', label: 'Product', width: '35%' },
                { key: 'sku', label: 'SKU', width: '20%' },
                { key: 'price', label: 'Price', width: '15%' },
                { key: 'stock', label: 'Stock', width: '15%' },
                { key: 'status', label: 'Status', width: '15%' }
            ],
            'ordersVendor': [
                { key: 'order_id', label: 'Order #', width: '20%' },
                { key: 'customer', label: 'Customer', width: '25%' },
                { key: 'items', label: 'Items', width: '25%' },
                { key: 'total', label: 'Total', width: '15%' },
                { key: 'status', label: 'Status', width: '15%' }
            ],
            'books': [
                { key: 'title', label: 'Book', width: '35%' },
                { key: 'format', label: 'Format', width: '20%' },
                { key: 'price', label: 'Price', width: '15%' },
                { key: 'sales', label: 'Sales', width: '15%' },
                { key: 'status', label: 'Status', width: '15%' }
            ],
            'royalties': [
                { key: 'book_title', label: 'Month', width: '25%' },
                { key: 'earnings', label: 'Amount', width: '25%' },
                { key: 'month_earnings', label: 'Currency', width: '20%' },
                { key: 'total_sales', label: 'Status', width: '20%' },
                { key: 'last_sale_date', label: 'Paid Date', width: '10%' }
            ],
            'keeperAnalytics': [
                { key: 'metric', label: 'Metric', width: '35%' },
                { key: 'value', label: 'Value', width: '25%' },
                { key: 'change', label: 'Change', width: '20%' },
                { key: 'trend', label: 'Trend', width: '20%' }
            ]
        };
        
        // Get column configuration or use default
        const columns = columnConfigs[type] || Object.keys(data[0]).map(key => ({
            key: key,
            label: key.charAt(0).toUpperCase() + key.slice(1).replace(/_/g, ' '),
            width: 'auto'
        }));
        
        // ===== BUILD TABLE HTML =====
        let tableHTML = `
            <div class="section-title">${title}</div>
            <div style="overflow: auto; max-height: 65vh;">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            ${columns.map(col => `<th style="width: ${col.width}">${col.label}</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        // Add rows
        data.forEach(item => {
            tableHTML += '<tr>';
            columns.forEach(col => {
                const value = item[col.key] !== undefined ? item[col.key] : '';
                const formattedValue = col.format ? col.format(value, item) : value;
                tableHTML += `<td>${formattedValue}</td>`;
            });
            tableHTML += '</tr>';
        });
        
        tableHTML += `
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 6px; text-align: center; color: #666; font-size: 14px;">
                Showing ${data.length} ${data.length === 1 ? 'item' : 'items'}
            </div>
        `;
        
        container.innerHTML = tableHTML;
    }
    
    // ===== ADD CLICK LISTENERS TO ALL TILES =====
    document.querySelectorAll('.dashboard-tile').forEach(tile => {
        tile.addEventListener('click', function() {
            const title = this.querySelector('.dashboard-title').textContent;
            const key = this.dataset.key;
            if (key) {
                openDrawer(title, key);
            }
        });
    });
    
    // ===== CLOSE DRAWER WHEN CLICKING CLOSE BUTTON =====
    document.querySelector('.dashboard-close')?.addEventListener('click', closeDrawer);
    
    // ===== CLOSE DRAWER WHEN CLICKING OVERLAY =====
    document.querySelector('.dashboard-overlay')?.addEventListener('click', closeDrawer);
    
    // ===== CLOSE DRAWER WITH ESCAPE KEY =====
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDrawer();
        }
    });
    
    // ===== PREVENT DRAWER CLOSE WHEN CLICKING INSIDE DRAWER =====
    document.getElementById('dashboard-drawer')?.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // ===== LOG FOR DEBUGGING =====
    console.log('Dashboard initialized successfully');
    console.log('Available data:', {
        seeker: Object.keys(window.seekerData),
        creator: Object.keys(window.creatorData),
        keeper: Object.keys(window.keeperData)
    });
});

// ===== MAKE FUNCTIONS AVAILABLE GLOBALLY (for onclick attributes) =====
function closeDrawer() {
    const drawer = document.getElementById('dashboard-drawer');
    if (drawer) {
        drawer.classList.remove('open');
        document.body.style.overflow = '';
    }
}
</script>