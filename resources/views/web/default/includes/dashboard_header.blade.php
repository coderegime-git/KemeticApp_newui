
<!-- <div class="mobile-overlay"></div>
<button class="mobile-menu-toggle">
  <span class="dashboard-ms">menu</span>
</button>
<aside class="dashboard-side">
    <div class="dashboard-brand">
     
      <div class="dashboard-brand-badge"> @if(auth()->user())
        <img src="{{ $authUser->getAvatar() }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}" width="50" onerror="this.src='https://placehold.co/40x40?text=K'">
        @endif
      </div>
      <div>
        <div style="font-weight:900"> @if(auth()->user()) {{ $authUser->full_name }} @endif</div>
        <div class="dashboard-muted" style="font-size:12px"> @if(auth()->user()) {{ $authUser->role->caption }} @endif</div>
      </div>
    </div>
    <nav class="dashboard-nav">
        @if(auth()->user())
          @if($authUser->isAdmin())
            <a href="/"><span class="dashboard-ms">home</span> Home</a>
            <a href="{{ getAdminPanelUrl() }}" class="active"><span class="dashboard-ms">space_dashboard</span> Dashboard</a>
            <a href="{{ getAdminPanelUrl("/settings") }}"><span class="dashboard-ms">settings</span> Settings</a>
            @else
            <a href="/"><span class="dashboard-ms">home</span> Home</a>
            <a href="/panel" class="{{ Request::is('panel') && !Request::is('panel/*') ? 'active' : '' }}"><span class="dashboard-ms">space_dashboard</span> Dashboard</a>
            <a href="@if(auth()->user()) {{ $authUser->getProfileUrl() }} @endif" class="{{ Request::is('users/*') ? 'active' : '' }}"><span class="dashboard-ms">person</span> Profile</a>
            <a href="/panel/notifications" class="{{ Request::is('panel/notifications*') ? 'active' : '' }}"><span class="dashboard-ms">notifications</span> Notifications</a>
            <a href="/panel/setting" class="{{ Request::is('panel/setting*') ? 'active' : '' }}"><span class="dashboard-ms">settings</span> Settings</a>
          @endif
        @else
            <a href="/"><span class="dashboard-ms">home</span> Home</a>
            <a href="/panel" class="{{ Request::is('panel') && !Request::is('panel/*') ? 'active' : '' }}"><span class="dashboard-ms">space_dashboard</span> Dashboard</a>
            <a href="@if(auth()->user()) {{ $authUser->getProfileUrl() }} @endif" class="{{ Request::is('users/*') ? 'active' : '' }}"><span class="dashboard-ms">person</span> Profile</a>
            <a href="/panel/notifications" class="{{ Request::is('panel/notifications*') ? 'active' : '' }}"><span class="dashboard-ms">notifications</span> Notifications</a>
            <a href="/panel/setting" class="{{ Request::is('panel/setting*') ? 'active' : '' }}"><span class="dashboard-ms">settings</span> Settings</a>
        @endif
            <a href="/logout"><span class="dashboard-ms">logout</span> Logout</a>
    </nav>
    <div style="margin-top:auto">
      <div class="dashboard-chakra">
        <i style="background:var(--red)"></i><i style="background:var(--orange)"></i><i style="background:var(--yellow)"></i>
        <i style="background:var(--green)"></i><i style="background:var(--blue)"></i><i style="background:var(--indigo)"></i><i style="background:var(--violet)"></i>
      </div>
    </div>
</aside> -->

<style>

        :root {
            --sidebar-width: 280px;
            --sidebar-bg: linear-gradient(180deg, #0f172a 0%, #020617 100%);
            --sidebar-card: rgba(255,255,255,0.04);
            --sidebar-border: rgba(255,255,255,0.08);

            --sidebar-text: #e5e7eb;
            --sidebar-muted: #9ca3af;

            --sidebar-hover: rgba(255,255,255,0.06);
            --sidebar-active: linear-gradient(135deg, #191920, #111111);
            --sidebar-active-text: #ffffff;

            --gold: #facc15;
            --mobile-breakpoint: 768px;
        }

        /* Mobile Overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(2,6,23,0.65);
            backdrop-filter: blur(2px);
            z-index: 999;
        }

        .mobile-overlay.active {
            display: block;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 14px;
            left: 14px;
            z-index: 1001;
            background: var(--sidebar-active);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 22px;
            box-shadow: 0 10px 30px rgba(99,102,241,.35);
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }

        /* Dashboard Sidebar */
        .dashboard-side {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sidebar-border);
            transition: transform .35s ease;
        }

        @media (max-width: 768px) {
            .dashboard-side {
                transform: translateX(-100%);
            }
            .dashboard-side.mobile-open {
                transform: translateX(0);
            }
        }

        /* Dashboard Brand */
        .dashboard-brand {
            padding: 22px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .dashboard-brand-badge {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(250,204,21,.25);
        }

        .dashboard-brand-badge img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Dashboard Navigation */
        .dashboard-nav {
            flex: 1;
            padding: 18px 14px;
            overflow-y: auto;
        }


        .dashboard-nav a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 6px;
            background: transparent;
            transition: all .25s ease;
        }

        .dashboard-nav a:hover {
            background: var(--sidebar-hover);
        }

        .dashboard-nav a.active {
            background: var(--sidebar-active);
            color:white;
            font-weight: 600;
        }

        /* Collapsible Menu Styles */
        .nav-collapsible {
            position: relative;
        }

        .nav-collapsible-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-collapsible-toggle:hover {
            background: var(--sidebar-hover);
        }

        .nav-collapsible-toggle.active {
            background: var(--sidebar-active);
            color: white;
            font-weight: 600;
        }

        .nav-arrow {
            transform: rotate(0deg);
            transition: transform 0.3s ease;
            font-size: 18px;
            color: var(--sidebar-muted);
        }

        .nav-collapsible.open .nav-arrow {
            transform: rotate(90deg);
            color: var(--gold);
        }

        .nav-collapsible-content {
            display: none;
            margin: 6px 0 10px 14px;
            padding: 8px;
            background: var(--sidebar-card);
            border-radius: 12px;
            border: 1px solid var(--sidebar-border);
        }

        .nav-collapsible.open .nav-collapsible-content {
            display: block;
        }

        .nav-collapsible-content a {
            padding: 10px 14px;
            font-size: 14px;
            color: var(--sidebar-muted);
            border-radius: 10px;
        }

        .nav-collapsible-content a:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .nav-collapsible-content a.active {
            background: rgba(99,102,241,.25);
            color: white;
        }

        /* Badge for notifications */
        .nav-badge {
            background: var(--gold);
            color: #020617;
            border-radius: 999px;
            font-size: 11px;
            padding: 2px 8px;
            margin-left: auto;
            font-weight: 600;
        }

        /* Section titles */
        .nav-section {
            margin: 20px 0 10px 0;
        }

        .nav-section-title {
            padding: 10px 16px;
            font-size: 11px;
            letter-spacing: .12em;
            color: var(--sidebar-muted);
            text-transform: uppercase;
            margin: 18px 0 10px;
        }

        /* Icons */
        .dashboard-ms {
            font-size: 20px;
            width: 24px;
            text-align: center;
            color: #c7d2fe;
        }

        /* Dashboard Chakra */
        .dashboard-chakra {
            padding: 20px;
            display: flex;
            gap: 4px;
            justify-content: center;
        }

        .dashboard-chakra i {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Main Content Area */
        .dashboard-main {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
            background: linear-gradient(180deg, #060606, #171819);
        }

        @media (max-width: 768px) {
            .dashboard-main {
                margin-left: 0;
                padding: 15px;
            }
        }

        /* Dashboard Header */
        .dashboard-header {
            background: white;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        /* Custom Scrollbar */
        .dashboard-nav::-webkit-scrollbar {
            width: 4px;
        }

        .dashboard-nav::-webkit-scrollbar-track {
           background: rgba(255,255,255,.15);
            border-radius: 2px;
        }

        .dashboard-nav::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2px;
        }

        .dashboard-nav::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Material Icons */
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
        }

        /* Dashboard Muted Text */
        .dashboard-muted {
           color: var(--sidebar-muted);
            font-size: 12px;
        }
</style>

@php
    $getPanelSidebarSettings = getPanelSidebarSettings();
@endphp

<div class="mobile-overlay"></div>
<button class="mobile-menu-toggle">
    <span class="dashboard-ms">menu</span>
</button>
<aside class="dashboard-side">
    <div class="dashboard-brand">
        <div class="dashboard-brand-badge">
            @if(auth()->user())
                <img src="{{ $authUser->getAvatar(50) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}" width="50" onerror="this.src='https://placehold.co/40x40?text=K'">
            @endif
        </div>
        <div>
            <div style="font-weight:900">
                @if(auth()->user())
                    {{ $authUser->full_name }}
                @endif
            </div>
            <div class="dashboard-muted" style="font-size:12px">
                @if(auth()->user())
                    @if(!empty($authUser->getUserGroup()))
                        {{ $authUser->getUserGroup()->name }}
                    @else
                        {{ $authUser->role->caption ?? '' }}
                    @endif
                @endif
            </div>
        </div>
    </div>

    <nav class="dashboard-nav">
        <!-- Dashboard -->
         <a href="/"><span class="dashboard-ms">home</span> Home</a>
         @if(auth()->user())
        <a href="/panel" class="{{ Request::is('panel') && !Request::is('panel/*') ? 'active' : '' }}">
            <span class="dashboard-ms">space_dashboard</span> {{ trans('panel.dashboard') }}
        </a>

        
        <a href="{{ route('reels.index') }}" class="{{ Request::is('reels*') ? 'active' : '' }}">
            <span class="dashboard-ms">movie</span> Reels
        </a>
        @endif
        <!-- ========== COURSES ========== -->
        @can('panel_webinars')
        <div class="nav-collapsible {{ Request::is('panel/webinars*') ? 'open' : '' }}">
            <a class="nav-collapsible-toggle {{ Request::is('panel/webinars*') ? 'active' : '' }}">
                <span class="dashboard-ms">school</span> {{ trans('panel.webinars') }}
                <span class="nav-arrow">›</span>
            </a>
            <div class="nav-collapsible-content">
                <!-- For Teachers/Organizations -->
                @if(auth()->user())
                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        @can('panel_webinars_create')
                            <a href="/panel/webinars/new" class="{{ Request::is('panel/webinars/new') ? 'active' : '' }}">
                                <span class="dashboard-ms">add_circle</span> {{ trans('public.new') }}
                            </a>
                        @endcan
                        @can('panel_webinars_lists')
                            <a href="/panel/webinars" class="{{ Request::is('panel/webinars') && !Request::is('panel/webinars/*') ? 'active' : '' }}">
                                <span class="dashboard-ms">list</span> {{ trans('panel.my_classes') }}
                            </a>
                        @endcan
                        @can('panel_webinars_invited_lists')
                            <a href="/panel/webinars/invitations" class="{{ Request::is('panel/webinars/invitations') ? 'active' : '' }}">
                                <span class="dashboard-ms">group_add</span> {{ trans('panel.invited_classes') }}
                            </a>
                        @endcan
                    @endif
                @endif

                <!-- Organization Classes -->
                @if(!empty($authUser->organ_id))
                    @can('panel_webinars_organization_classes')
                        <a href="/panel/webinars/organization_classes" class="{{ Request::is('panel/webinars/organization_classes') ? 'active' : '' }}">
                            <span class="dashboard-ms">business</span> {{ trans('panel.organization_classes') }}
                        </a>
                    @endcan
                @endif

                <!-- My Purchases -->
                @can('panel_webinars_my_purchases')
                    <a href="/panel/webinars/purchases" class="{{ Request::is('panel/webinars/purchases') ? 'active' : '' }}">
                        <span class="dashboard-ms">shopping_cart</span> {{ trans('panel.my_purchases') }}
                    </a>
                @endcan

                <!-- Comments -->
                @if($authUser->isOrganization() || $authUser->isTeacher())
                    @can('panel_webinars_my_class_comments')
                        <a href="/panel/webinars/comments" class="{{ Request::is('panel/webinars/comments') ? 'active' : '' }}">
                            <span class="dashboard-ms">comment</span> {{ trans('panel.my_class_comments') }}
                        </a>
                    @endcan
                @endif

                <!-- My Comments -->
                @can('panel_webinars_comments')
                    <a href="/panel/webinars/my-comments" class="{{ Request::is('panel/webinars/my-comments') ? 'active' : '' }}">
                        <span class="dashboard-ms">forum</span> {{ trans('panel.my_comments') }}
                    </a>
                @endcan

                <!-- Favorites -->
                @can('panel_webinars_favorites')
                    <a href="/panel/webinars/favorites" class="{{ Request::is('panel/webinars/favorites') ? 'active' : '' }}">
                        <span class="dashboard-ms">favorite</span> {{ trans('panel.favorites') }}
                    </a>
                @endcan

                <!-- Course Notes -->
                @if(!empty(getFeaturesSettings('course_notes_status')))
                    @can('panel_webinars_personal_course_notes')
                        <a href="/panel/webinars/personal-notes" class="{{ Request::is('panel/webinars/personal-notes') ? 'active' : '' }}">
                            <span class="dashboard-ms">note</span> {{ trans('update.course_notes') }}
                        </a>
                    @endcan
                @endif
            </div>
        </div>
        @endcan

        <!-- ========== COURSE BUNDLES ========== -->
        @if(auth()->user())
            @if($authUser->isOrganization() || $authUser->isTeacher())
                @can('panel_bundles')
                <div class="nav-collapsible {{ Request::is('panel/bundles*') ? 'open' : '' }}">
                    <a class="nav-collapsible-toggle {{ Request::is('panel/bundles*') ? 'active' : '' }}">
                        <span class="dashboard-ms">package</span> {{ trans('update.bundles') }}
                        <span class="nav-arrow">›</span>
                    </a>
                    <div class="nav-collapsible-content">
                        @can('panel_bundles_create')
                            <a href="/panel/bundles/new" class="{{ Request::is('panel/bundles/new') ? 'active' : '' }}">
                                <span class="dashboard-ms">add_circle</span> {{ trans('public.new') }}
                            </a>
                        @endcan
                        @can('panel_bundles_lists')
                            <a href="/panel/bundles" class="{{ Request::is('panel/bundles') && !Request::is('panel/bundles/*') ? 'active' : '' }}">
                                <span class="dashboard-ms">list</span> {{ trans('update.my_bundles') }}
                            </a>
                        @endcan
                    </div>
                </div>
                @endcan
            @endif
        @endif
        <!-- ========== ASSIGNMENTS ========== -->
        @if(getFeaturesSettings('webinar_assignment_status'))
            @can('panel_assignments')
            <div class="nav-collapsible {{ Request::is('panel/assignments*') ? 'open' : '' }}">
                <a class="nav-collapsible-toggle {{ Request::is('panel/assignments*') ? 'active' : '' }}">
                    <span class="dashboard-ms">assignment</span> {{ trans('update.assignments') }}
                    <span class="nav-arrow">›</span>
                </a>
                <div class="nav-collapsible-content">
                    @can('panel_assignments_lists')
                        <a href="/panel/assignments/my-assignments" class="{{ Request::is('panel/assignments/my-assignments') ? 'active' : '' }}">
                            <span class="dashboard-ms">assignment</span> {{ trans('update.my_assignments') }}
                        </a>
                    @endcan
                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        @can('panel_assignments_my_courses_assignments')
                            <a href="/panel/assignments/my-courses-assignments" class="{{ Request::is('panel/assignments/my-courses-assignments') ? 'active' : '' }}">
                                <span class="dashboard-ms">group</span> {{ trans('update.students_assignments') }}
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
            @endcan
        @endif

        <!-- ========== UPCOMING COURSES ========== -->
        @if(!empty(getFeaturesSettings('upcoming_courses_status')))
            @can('panel_upcoming_courses')
            <div class="nav-collapsible {{ Request::is('panel/upcoming_courses*') ? 'open' : '' }}">
                <a class="nav-collapsible-toggle {{ Request::is('panel/upcoming_courses*') ? 'active' : '' }}">
                    <span class="dashboard-ms">schedule</span> {{ trans('update.upcoming_courses') }}
                    <span class="nav-arrow">›</span>
                </a>
                <div class="nav-collapsible-content">
                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        @can('panel_upcoming_courses_create')
                            <a href="/panel/upcoming_courses/new" class="{{ Request::is('panel/upcoming_courses/new') ? 'active' : '' }}">
                                <span class="dashboard-ms">add_circle</span> {{ trans('public.new') }}
                            </a>
                        @endcan
                        @can('panel_upcoming_courses_lists')
                            <a href="/panel/upcoming_courses" class="{{ Request::is('panel/upcoming_courses') && !Request::is('panel/upcoming_courses/*') ? 'active' : '' }}">
                                <span class="dashboard-ms">list</span> {{ trans('update.my_upcoming_courses') }}
                            </a>
                        @endcan
                    @endif
                    @can('panel_upcoming_courses_followings')
                        <a href="/panel/upcoming_courses/followings" class="{{ Request::is('panel/upcoming_courses/followings') ? 'active' : '' }}">
                            <span class="dashboard-ms">visibility</span> {{ trans('update.following_courses') }}
                        </a>
                    @endcan
                </div>
            </div>
            @endcan
        @endif

        <!-- ========== QUIZZES ========== -->
        @can('panel_quizzes')
        <div class="nav-collapsible {{ Request::is('panel/quizzes*') ? 'open' : '' }}">
            <a class="nav-collapsible-toggle {{ Request::is('panel/quizzes*') ? 'active' : '' }}">
                <span class="dashboard-ms">quiz</span> {{ trans('panel.quizzes') }}
                <span class="nav-arrow">›</span>
            </a>
            <div class="nav-collapsible-content">
                @if($authUser->isOrganization() || $authUser->isTeacher())
                    @can('panel_quizzes_create')
                        <a href="/panel/quizzes/new" class="{{ Request::is('panel/quizzes/new') ? 'active' : '' }}">
                            <span class="dashboard-ms">add_circle</span> {{ trans('quiz.new_quiz') }}
                        </a>
                    @endcan
                    @can('panel_quizzes_lists')
                        <a href="/panel/quizzes" class="{{ Request::is('panel/quizzes') && !Request::is('panel/quizzes/*') ? 'active' : '' }}">
                            <span class="dashboard-ms">list</span> {{ trans('public.list') }}
                        </a>
                    @endcan
                    @can('panel_quizzes_results')
                        <a href="/panel/quizzes/results" class="{{ Request::is('panel/quizzes/results') ? 'active' : '' }}">
                            <span class="dashboard-ms">analytics</span> {{ trans('public.results') }}
                        </a>
                    @endcan
                @endif
                @can('panel_quizzes_my_results')
                    <a href="/panel/quizzes/my-results" class="{{ Request::is('panel/quizzes/my-results') ? 'active' : '' }}">
                        <span class="dashboard-ms">grade</span> {{ trans('public.my_results') }}
                    </a>
                @endcan
                @can('panel_quizzes_not_participated')
                    <a href="/panel/quizzes/opens" class="{{ Request::is('panel/quizzes/opens') ? 'active' : '' }}">
                        <span class="dashboard-ms">pending</span> {{ trans('public.not_participated') }}
                    </a>
                @endcan
            </div>
        </div>
        @endcan

        <!-- ========== CERTIFICATES ========== -->
        @if(!empty(getCertificateMainSettings("status")))
            @can('panel_certificates')
            <div class="nav-collapsible {{ Request::is('panel/certificates*') ? 'open' : '' }}">
                <a class="nav-collapsible-toggle {{ Request::is('panel/certificates*') ? 'active' : '' }}">
                    <span class="dashboard-ms">badge</span> {{ trans('panel.certificates') }}
                    <span class="nav-arrow">›</span>
                </a>
                <div class="nav-collapsible-content">
                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        @can('panel_certificates_lists')
                            <a href="/panel/certificates" class="{{ Request::is('panel/certificates') && !Request::is('panel/certificates/*') ? 'active' : '' }}">
                                <span class="dashboard-ms">list</span> {{ trans('public.list') }}
                            </a>
                        @endcan
                    @endif
                    @can('panel_certificates_achievements')
                        <a href="/panel/certificates/achievements" class="{{ Request::is('panel/certificates/achievements') ? 'active' : '' }}">
                            <span class="dashboard-ms">emoji_events</span> {{ trans('quiz.achievements') }}
                        </a>
                    @endcan
                    <a href="/certificate_validation">
                        <span class="dashboard-ms">verified</span> {{ trans('site.certificate_validation') }}
                    </a>
                    @can('panel_certificates_course_certificates')
                        <a href="/panel/certificates/webinars" class="{{ Request::is('panel/certificates/webinars') ? 'active' : '' }}">
                            <span class="dashboard-ms">school</span> {{ trans('update.course_certificates') }}
                        </a>
                    @endcan
                </div>
            </div>
            @endcan
        @endif

        <!-- ========== MEETINGS ========== -->
        @can('panel_meetings')
        <div class="nav-collapsible {{ Request::is('panel/meetings*') ? 'open' : '' }}">
            <a class="nav-collapsible-toggle {{ Request::is('panel/meetings*') ? 'active' : '' }}">
                <span class="dashboard-ms">video_camera_front</span> {{ trans('panel.meetings') }}
                <span class="nav-arrow">›</span>
            </a>
            <div class="nav-collapsible-content">
                @can('panel_meetings_my_reservation')
                    <a href="/panel/meetings/reservation" class="{{ Request::is('panel/meetings/reservation') ? 'active' : '' }}">
                        <span class="dashboard-ms">event</span> {{ trans('public.my_reservation') }}
                    </a>
                @endcan
                @if($authUser->isOrganization() || $authUser->isTeacher())
                    @can('panel_meetings_requests')
                        <a href="/panel/meetings/requests" class="{{ Request::is('panel/meetings/requests') ? 'active' : '' }}">
                            <span class="dashboard-ms">request_quote</span> {{ trans('panel.requests') }}
                        </a>
                    @endcan
                    @can('panel_meetings_settings')
                        <a href="/panel/meetings/settings" class="{{ Request::is('panel/meetings/settings') ? 'active' : '' }}">
                            <span class="dashboard-ms">settings</span> {{ trans('panel.settings') }}
                        </a>
                    @endcan
                @endif
            </div>
        </div>
        @endcan

        <!-- ========== STORE/PRODUCTS ========== -->
        @can('panel_products')
        <div class="nav-collapsible {{ Request::is('panel/store*') ? 'open' : '' }}">
            <a class="nav-collapsible-toggle {{ Request::is('panel/store*') ? 'active' : '' }}">
                <span class="dashboard-ms">store</span> {{ trans('update.store') }}
                <span class="nav-arrow">›</span>
            </a>
            <div class="nav-collapsible-content">
                @if($authUser->isOrganization() || $authUser->isTeacher())
                    @can('panel_products_create')
                        <a href="/panel/store/products/new" class="{{ Request::is('panel/store/products/new') ? 'active' : '' }}">
                            <span class="dashboard-ms">add_circle</span> {{ trans('update.new_product') }}
                        </a>
                    @endcan
                    @can('panel_products_lists')
                        <a href="/panel/store/products" class="{{ Request::is('panel/store/products') && !Request::is('panel/store/products/*') ? 'active' : '' }}">
                            <span class="dashboard-ms">list</span> {{ trans('update.products') }}
                        </a>
                    @endcan
                    @php
                        $sellerProductOrderWaitingDeliveryCount = $authUser->getWaitingDeliveryProductOrdersCount();
                    @endphp
                    @can('panel_products_sales')
                        <a href="/panel/store/sales" class="{{ Request::is('panel/store/sales') ? 'active' : '' }}">
                            <span class="dashboard-ms">shopping_cart</span> {{ trans('panel.sales') }}
                            @if($sellerProductOrderWaitingDeliveryCount > 0)
                                <span class="nav-badge">{{ $sellerProductOrderWaitingDeliveryCount }}</span>
                            @endif
                        </a>
                    @endcan
                @endif
                @can('panel_products_purchases')
                    <a href="/panel/store/purchases" class="{{ Request::is('panel/store/purchases') ? 'active' : '' }}">
                        <span class="dashboard-ms">shopping_bag</span> {{ trans('panel.my_purchases') }}
                    </a>
                @endcan
                @if($authUser->isOrganization() || $authUser->isTeacher())
                    @can('panel_products_comments')
                        <a href="/panel/store/products/comments" class="{{ Request::is('panel/store/products/comments') ? 'active' : '' }}">
                            <span class="dashboard-ms">comment</span> {{ trans('update.product_comments') }}
                        </a>
                    @endcan
                @endif
                @can('panel_products_my_comments')
                    <a href="/panel/store/products/my-comments" class="{{ Request::is('panel/store/products/my-comments') ? 'active' : '' }}">
                        <span class="dashboard-ms">forum</span> {{ trans('panel.my_comments') }}
                    </a>
                @endcan
            </div>
        </div>
        @endcan

        <!-- ========== FINANCIAL ========== -->
        @can('panel_financial')
        <div class="nav-collapsible {{ Request::is('panel/financial*') ? 'open' : '' }}">
            <a class="nav-collapsible-toggle {{ Request::is('panel/financial*') ? 'active' : '' }}">
                <span class="dashboard-ms">payments</span> {{ trans('panel.financial') }}
                <span class="nav-arrow">›</span>
            </a>
            <div class="nav-collapsible-content">
                @if($authUser->isOrganization() || $authUser->isTeacher())
                    @can('panel_financial_sales_reports')
                        <a href="/panel/financial/sales" class="{{ Request::is('panel/financial/sales') ? 'active' : '' }}">
                            <span class="dashboard-ms">analytics</span> {{ trans('financial.sales_report') }}
                        </a>
                    @endcan
                @endif
                @can('panel_financial_summary')
                    <a href="/panel/financial/summary" class="{{ Request::is('panel/financial/summary') ? 'active' : '' }}">
                        <span class="dashboard-ms">summarize</span> {{ trans('financial.financial_summary') }}
                    </a>
                @endcan
                @can('panel_financial_payout')
                    <a href="/panel/financial/payout" class="{{ Request::is('panel/financial/payout') ? 'active' : '' }}">
                        <span class="dashboard-ms">paid</span> {{ trans('financial.payout') }}
                    </a>
                @endcan
                @can('panel_financial_charge_account')
                    <a href="/panel/financial/account" class="{{ Request::is('panel/financial/account') ? 'active' : '' }}">
                        <span class="dashboard-ms">account_balance</span> {{ trans('financial.charge_account') }}
                    </a>
                @endcan
                @can('panel_financial_subscribes')
                    <a href="/panel/financial/subscribes" class="{{ Request::is('panel/financial/subscribes') ? 'active' : '' }}">
                        <span class="dashboard-ms">subscriptions</span> {{ trans('financial.subscribes') }}
                    </a>
                @endcan
                @if(($authUser->isOrganization() || $authUser->isTeacher()) and getRegistrationPackagesGeneralSettings('status'))
                    @can("panel_financial_registration_packages")
                        <a href="{{ route('panelRegistrationPackagesLists') }}" class="{{ Request::is('panel/financial/registration-packages') ? 'active' : '' }}">
                            <span class="dashboard-ms">card_membership</span> {{ trans('update.registration_packages') }}
                        </a>
                    @endcan
                @endif
                @if(getInstallmentsSettings('status'))
                    @can('panel_financial_installments')
                        <a href="/panel/financial/installments" class="{{ Request::is('panel/financial/installments*') ? 'active' : '' }}">
                            <span class="dashboard-ms">credit_score</span> {{ trans('update.installments') }}
                        </a>
                    @endcan
                @endif
            </div>
        </div>
        @endcan

        <!-- ========== ORGANIZATION MANAGEMENT ========== -->
        @if(auth()->user())
            @if($authUser->isOrganization())
                <div class="nav-section">
                    <div class="nav-section-title">
                        <span class="dashboard-ms">business</span> Organization
                    </div>
                    
                    @can('panel_organization_students')
                    <div class="nav-collapsible {{ Request::is('panel/students*') || Request::is('panel/manage/students*') ? 'open' : '' }}">
                        <a class="nav-collapsible-toggle {{ Request::is('panel/students*') || Request::is('panel/manage/students*') ? 'active' : '' }}">
                            <span class="dashboard-ms">school</span> {{ trans('quiz.students') }}
                            <span class="nav-arrow">›</span>
                        </a>
                        <div class="nav-collapsible-content">
                            @can('panel_organization_students_create')
                                <a href="/panel/manage/students/new" class="{{ Request::is('panel/manage/students/new') ? 'active' : '' }}">
                                    <span class="dashboard-ms">person_add</span> {{ trans('public.new') }}
                                </a>
                            @endcan
                            @can('panel_organization_students_lists')
                                <a href="/panel/manage/students" class="{{ Request::is('panel/manage/students') ? 'active' : '' }}">
                                    <span class="dashboard-ms">list</span> {{ trans('public.list') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                    @endcan

                    @can('panel_organization_instructors')
                    <div class="nav-collapsible {{ Request::is('panel/instructors*') || Request::is('panel/manage/instructors*') ? 'open' : '' }}">
                        <a class="nav-collapsible-toggle {{ Request::is('panel/instructors*') || Request::is('panel/manage/instructors*') ? 'active' : '' }}">
                            <span class="dashboard-ms">people</span> {{ trans('public.instructors') }}
                            <span class="nav-arrow">›</span>
                        </a>
                        <div class="nav-collapsible-content">
                            @can('panel_organization_instructors_create')
                                <a href="/panel/manage/instructors/new" class="{{ Request::is('panel/instructors/new') ? 'active' : '' }}">
                                    <span class="dashboard-ms">person_add</span> {{ trans('public.new') }}
                                </a>
                            @endcan
                            @can('panel_organization_instructors_lists')
                                <a href="/panel/manage/instructors" class="{{ Request::is('panel/manage/instructors') ? 'active' : '' }}">
                                    <span class="dashboard-ms">list</span> {{ trans('public.list') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                    @endcan
                </div>
            @endif
        @endif
        

        <!-- ========== SUPPORT ========== -->
        @can('panel_support')
        <div class="nav-collapsible {{ Request::is('panel/support*') ? 'open' : '' }}">
            <a class="nav-collapsible-toggle {{ Request::is('panel/support*') ? 'active' : '' }}">
                <span class="dashboard-ms">support_agent</span> {{ trans('panel.support') }}
                <span class="nav-arrow">›</span>
            </a>
            <div class="nav-collapsible-content">
                @can('panel_support_create')
                    <a href="/panel/support/new" class="{{ Request::is('panel/support/new') ? 'active' : '' }}">
                        <span class="dashboard-ms">add_circle</span> {{ trans('public.new') }}
                    </a>
                @endcan
                @can('panel_support_lists')
                    <a href="/panel/support" class="{{ Request::is('panel/support') && !Request::is('panel/support/*') ? 'active' : '' }}">
                        <span class="dashboard-ms">list</span> {{ trans('panel.classes_support') }}
                    </a>
                @endcan
                @can('panel_support_tickets')
                    <a href="/panel/support/tickets" class="{{ Request::is('panel/support/tickets') ? 'active' : '' }}">
                        <span class="dashboard-ms">confirmation_number</span> {{ trans('panel.support_tickets') }}
                    </a>
                @endcan
            </div>
        </div>
        @endcan

        <!-- ========== MARKETING ========== -->
        @if(auth()->user())
            @if(!$authUser->isUser() or (!empty($referralSettings) and $referralSettings['status'] and $authUser->affiliate) or (!empty(getRegistrationBonusSettings('status')) and $authUser->enable_registration_bonus))
                @can('panel_marketing')
                <div class="nav-collapsible {{ Request::is('panel/marketing*') ? 'open' : '' }}">
                    <a class="nav-collapsible-toggle {{ Request::is('panel/marketing*') ? 'active' : '' }}">
                        <span class="dashboard-ms">campaign</span> {{ trans('panel.marketing') }}
                        <span class="nav-arrow">›</span>
                    </a>
                    <div class="nav-collapsible-content">
                        @if(!$authUser->isUser())
                            @can('panel_marketing_special_offers')
                                <a href="/panel/marketing/special_offers" class="{{ Request::is('panel/marketing/special_offers') ? 'active' : '' }}">
                                    <span class="dashboard-ms">campaign</span> {{ trans('panel.discounts') }}
                                </a>
                            @endcan
                            @can('panel_marketing_promotions')
                                <a href="/panel/marketing/promotions" class="{{ Request::is('panel/marketing/promotions') ? 'active' : '' }}">
                                    <span class="dashboard-ms">campaign</span> {{ trans('panel.promotions') }}
                                </a>
                            @endcan
                        @endif
                        @if(!empty($referralSettings) and $referralSettings['status'] and $authUser->affiliate)
                            @can('panel_marketing_affiliates')
                                <a href="/panel/marketing/affiliates" class="{{ Request::is('panel/marketing/affiliates') ? 'active' : '' }}">
                                    <span class="dashboard-ms">share</span> {{ trans('panel.affiliates') }}
                                </a>
                            @endcan
                        @endif
                        @if(!empty(getRegistrationBonusSettings('status')) and $authUser->enable_registration_bonus)
                            @can('panel_marketing_registration_bonus')
                                <a href="/panel/marketing/registration_bonus" class="{{ Request::is('panel/marketing/registration_bonus') ? 'active' : '' }}">
                                    <span class="dashboard-ms">celebration</span> {{ trans('update.registration_bonus') }}
                                </a>
                            @endcan
                        @endif
                        @if(!empty(getFeaturesSettings('frontend_coupons_status')))
                            @can('panel_marketing_coupons')
                                <a href="/panel/marketing/discounts" class="{{ Request::is('panel/marketing/discounts') ? 'active' : '' }}">
                                    <span class="dashboard-ms">local_offer</span> {{ trans('update.coupons') }}
                                </a>
                            @endcan
                            @can('panel_marketing_new_coupon')
                                <a href="/panel/marketing/discounts/new" class="{{ Request::is('panel/marketing/discounts/new') ? 'active' : '' }}">
                                    <span class="dashboard-ms">add_circle</span> {{ trans('update.new_coupon') }}
                                </a>
                            @endcan
                        @endif
                    </div>
                </div>
                @endcan
            @endif
        @endif

        <!-- ========== FORUMS ========== -->
        @if(getFeaturesSettings('forums_status'))
            @can('panel_forums')
            <div class="nav-collapsible {{ Request::is('panel/forums*') || Request::is('forums/create-topic') ? 'open' : '' }}">
                <a class="nav-collapsible-toggle {{ Request::is('panel/forums*') || Request::is('forums/create-topic') ? 'active' : '' }}">
                    <span class="dashboard-ms">forum</span> {{ trans('update.forums') }}
                    <span class="nav-arrow">›</span>
                </a>
                <div class="nav-collapsible-content">
                    @can('panel_forums_new_topic')
                        <a href="/forums/create-topic" class="{{ Request::is('forums/create-topic') ? 'active' : '' }}">
                            <span class="dashboard-ms">add_circle</span> {{ trans('update.new_topic') }}
                        </a>
                    @endcan
                    @can('panel_forums_my_topics')
                        <a href="/panel/forums/topics" class="{{ Request::is('panel/forums/topics') ? 'active' : '' }}">
                            <span class="dashboard-ms">topic</span> {{ trans('update.my_topics') }}
                        </a>
                    @endcan
                    @can('panel_forums_my_posts')
                        <a href="/panel/forums/posts" class="{{ Request::is('panel/forums/posts') ? 'active' : '' }}">
                            <span class="dashboard-ms">comment</span> {{ trans('update.my_posts') }}
                        </a>
                    @endcan
                    @can('panel_forums_bookmarks')
                        <a href="/panel/forums/bookmarks" class="{{ Request::is('panel/forums/bookmarks') ? 'active' : '' }}">
                            <span class="dashboard-ms">bookmark</span> {{ trans('update.bookmarks') }}
                        </a>
                    @endcan
                </div>
            </div>
            @endcan
        @endif

        @if(auth()->user())
            @if($authUser->isTeacher())
                @can('panel_blog')
                    <div class="nav-collapsible {{ (request()->is('panel/blog') or request()->is('panel/blog/*')) ? 'open' : '' }}">
                        <a class="nav-collapsible-toggle {{ (request()->is('panel/blog') or request()->is('panel/blog/*')) ? 'active' : '' }}">
                            <span class="dashboard-ms">article</span> {{ trans('update.articles') }}
                            <span class="nav-arrow">›</span>
                        </a>
                        <div class="nav-collapsible-content">
                            @can('panel_blog_new_article')
                                <a href="/panel/blog/posts/new" class="{{ (request()->is('panel/blog/posts/new')) ? 'active' : '' }}">
                                    <span class="dashboard-ms">add_circle</span> {{ trans('update.new_article') }}
                                </a>
                            @endcan

                            @can('panel_blog_my_articles')
                                <a href="/panel/blog/posts" class="{{ (request()->is('panel/blog/posts')) ? 'active' : '' }}">
                                    <span class="dashboard-ms">library_books</span> {{ trans('update.my_articles') }}
                                </a>
                            @endcan

                            @can('panel_blog_comments')
                                <a href="/panel/blog/comments" class="{{ (request()->is('panel/blog/comments')) ? 'active' : '' }}">
                                    <span class="dashboard-ms">comment</span> {{ trans('panel.comments') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                    @endcan
            @endif

            @if($authUser->isTeacher())
            <div class="nav-collapsible {{ (request()->is('panel/book') or request()->is('panel/book/*')) ? 'open' : '' }}">
                <a class="nav-collapsible-toggle {{ (request()->is('panel/book') or request()->is('panel/book/*')) ? 'active' : '' }}">
                    <span class="dashboard-ms">book</span>Book
                    <span class="nav-arrow">›</span>
                </a>
                <div class="nav-collapsible-content">
                    <a href="/panel/book/new" class="{{ (request()->is('panel/book/new')) ? 'active' : '' }}">
                        <span class="dashboard-ms">add_circle</span> New Book
                    </a>

                    <a href="/panel/book/" class="{{ (request()->is('panel/book/')) ? 'active' : '' }}">
                        <span class="dashboard-ms">library_books</span> My Book
                    </a>
                </div>
            </div>
            @endif

            @if($authUser->isOrganization() || $authUser->isTeacher())
                @can('panel_noticeboard')
                <div class="nav-collapsible {{ (request()->is('panel/noticeboard*') or request()->is('panel/course-noticeboard*')) ? 'open' : '' }}">
                    <a class="nav-collapsible-toggle {{ (request()->is('panel/noticeboard*') or request()->is('panel/course-noticeboard*')) ? 'active' : '' }}">
                        <span class="dashboard-ms">notifications</span> {{ trans('panel.noticeboard') }}
                        <span class="nav-arrow">›</span>
                    </a>
                    <div class="nav-collapsible-content">
                        @can('panel_noticeboard_history')
                            <a href="/panel/noticeboard" class="{{ (request()->is('panel/noticeboard')) ? 'active' : '' }}">
                                <span class="dashboard-ms">history</span> {{ trans('public.history') }}
                            </a>
                        @endcan

                        @can('panel_noticeboard_create')
                            <a href="/panel/noticeboard/new" class="{{ (request()->is('panel/noticeboard/new')) ? 'active' : '' }}">
                                <span class="dashboard-ms">add_circle</span> {{ trans('public.new') }}
                            </a>
                        @endcan

                        @can('panel_noticeboard_course_notices')
                            <a href="/panel/course-noticeboard" class="{{ (request()->is('panel/course-noticeboard')) ? 'active' : '' }}">
                                <span class="dashboard-ms">menu_book</span> {{ trans('update.course_notices') }}
                            </a>
                        @endcan

                        @can('panel_noticeboard_course_notices_create')
                            <a href="/panel/course-noticeboard/new" class="{{ (request()->is('panel/course-noticeboard/new')) ? 'active' : '' }}">
                                <span class="dashboard-ms">add_circle</span> {{ trans('update.new_course_notices') }}
                            </a>
                        @endcan
                    </div>
                </div>
                @endcan
            @endif

            @php
                $rewardSetting = getRewardsSettings();
            @endphp

            @if(!empty($rewardSetting) and $rewardSetting['status'] == '1')
                @can('panel_rewards')
                    <a href="/panel/rewards" class="{{ (request()->is('panel/rewards')) ? 'active' : '' }}">
                        <span class="dashboard-ms">military_tech</span> {{ trans('update.rewards') }}
                    </a>
                @endcan
            @endif

            @if($authUser->checkAccessToAIContentFeature())
                @can('panel_ai_contents')
                <div class="nav-collapsible {{ (request()->is('panel/ai-contents')) ? 'open' : '' }}">
                    <a href="/panel/ai-contents" class="nav-collapsible-toggle {{ (request()->is('panel/ai-contents')) ? 'active' : '' }}">
                        <span class="dashboard-ms">smart_toy</span> {{ trans('update.ai_contents') }}
                    </a>
                </div>
                @endcan
            @endif
        
        

        <!-- ========== NOTIFICATIONS ========== -->
        @can('panel_notifications')
        <a href="/panel/notifications" class="{{ Request::is('panel/notifications*') ? 'active' : '' }}">
            <span class="dashboard-ms">notifications</span> {{ trans('panel.notifications') }}
        </a>
        @endcan

        <!-- ========== SETTINGS ========== -->
        @can('panel_others_profile_setting')
        <a href="/panel/setting" class="{{ Request::is('panel/setting*') ? 'active' : '' }}">
            <span class="dashboard-ms">settings</span> {{ trans('panel.settings') }}
        </a>
        @endcan
       

        <!-- ========== PROFILE ========== -->
       
            <a href="{{ $authUser->getProfileUrl() }}" class="{{ Request::is('users/*') ? 'active' : '' }}">
                <span class="dashboard-ms">person</span> {{ trans('public.my_profile') }}
            </a>
             <!-- @if($authUser->isTeacher() || $authUser->isOrganization()) -->
            <!-- @can('panel_others_profile_url') -->
            <!-- @endcan -->
        <!-- @endif -->

        <!-- ========== LOGOUT ========== -->
        @can('panel_others_logout')
        <a href="/logout">
            <span class="dashboard-ms">logout</span> {{ trans('panel.log_out') }}
        </a>
        @endcan
         @endif
    </nav>

    <div style="margin-top:auto">
        <div class="dashboard-chakra">
            <i style="background:var(--red)"></i><i style="background:var(--orange)"></i><i style="background:var(--yellow)"></i>
            <i style="background:var(--green)"></i><i style="background:var(--blue)"></i><i style="background:var(--indigo)"></i><i style="background:var(--violet)"></i>
        </div>
    </div>
</aside>