<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    @php
        $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
        $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    @endphp
    <head>
    <meta charset="UTF-8" />
    <title>Kemetic App – Header Popovers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    @include('web.default.includes.metas')
        <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
        <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

        <!-- Main App CSS -->
        <link rel="stylesheet" href="/assets/default/css/app.css">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300;400;600&display=swap"/>
    </head>
    <style>
        .app-popup {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }

        .app-popup.active {
            display: flex;
        }

        .app-popup-content {
            background: #1a1a1a;  /* Black background */
            border-radius: 20px;
            padding: 25px 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,215,0,0.3);
            position: relative;
            max-width: 400px;
            margin: 0 auto;
            border: 1px solid rgba(255, 215, 0, 0.2);
        }

        .app-popup-close {
            position: absolute;
            top: 12px;
            right: 15px;
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #FFD700;  /* Gold color */
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
            opacity: 0.8;
        }

        .app-popup-close:hover {
            background: rgba(255, 215, 0, 0.15);
            opacity: 1;
            transform: scale(1.1);
        }

        .app-popup-logo {
            text-align: center;
            margin-bottom: 15px;
        }

        .app-popup-logo img {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            border: 2px solid #FFD700;
        }

        .app-popup h4 {
            text-align: center;
            margin: 10px 0 5px;
            font-size: 20px;
            font-weight: 700;
            color: #FFD700;  /* Gold text */
            letter-spacing: 0.5px;
        }

        .app-popup p {
            text-align: center;
            color: #cccccc;  /* Light grey */
            margin-bottom: 20px;
            font-size: 14px;
        }

        .app-popup-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 15px;
        }

        .app-store-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 20px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
            max-width: 260px;
            margin: 0 auto;
            border: 1px solid transparent;
        }

        .app-store-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.25);
        }

        .android-btn {
            background: #2a2a2a;  /* Dark grey */
            color: #FFD700;  /* Gold text */
            border: 1px solid #FFD700;
        }

        .android-btn svg {
            color: #FFD700;
        }

        .android-btn:hover {
            background: #333333;
            border-color: #FFD700;
        }

        .ios-btn {
            background: #2a2a2a;  /* Dark grey */
            color: #FFD700;  /* Gold text */
            border: 1px solid #FFD700;
        }

        .ios-btn svg {
            color: #FFD700;
        }

        .ios-btn:hover {
            background: #333333;
            border-color: #FFD700;
        }

        .app-store-btn span {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
            font-size: 12px;
            color: #cccccc;
        }

        .app-store-btn span strong {
            color: #FFD700;
            font-size: 16px;
            font-weight: 700;
        }

        .app-popup-footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 215, 0, 0.2);
        }

        .app-popup-dont-show {
            color: #FFD700;
            text-decoration: none;
            font-size: 13px;
            opacity: 0.8;
            transition: all 0.3s;
            border-bottom: 1px dotted #FFD700;
            padding-bottom: 2px;
        }

        .app-popup-dont-show:hover {
            opacity: 1;
            color: #FFD700;
            border-bottom: 1px solid #FFD700;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes popIn {
            from { transform: scale(0.85); opacity: 0; }
            to   { transform: scale(1);    opacity: 1; }
        }

        /* Show only on mobile */
        @media (min-width: 768px) {
            .app-popup {
                display: none !important;
            }
        }

        /* Shine effect for gold elements */
        .app-popup-content::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #FFD700, transparent, #FFD700, transparent);
            border-radius: 22px;
            opacity: 0.1;
            pointer-events: none;
        }
    </style>
    <script src="//code.tidio.co/upn4h71nbeh0x6rgvilseh6g1ztchf35.js" async></script>
    <body class="@if(isset($isMembershipPage) && $isMembershipPage) membership-body @endif @if($isRtl) rtl @endif">

        <div id="app" class="app {{ (!empty($floatingBar) and $floatingBar->position == 'top' and $floatingBar->fixed) ? 'has-fixed-top-floating-bar' : '' }}">
            

            @include('web.default.includes.new_header')

            <main class="header-content">        
                <div class="header-sample">
                    <header class="topheader-kemetic-header">
                        <div class="topheader-header-left">
                            <div class="topheader-kemetic-logo">
                                    @if(!empty($generalSettings['logo']))
                                        <img src="{{ $generalSettings['logo'] }}" width="50" alt="site logo">
                                    @endif
                            </div>
                            <div class="topheader-kemetic-title">
                                <span class="topheader-name">Kemetic App</span>
                                <!-- <span class="topheader-tagline">@mythoughtsoneverything</span> -->
                            </div>
                        </div>

                        <div class="topheader-header-right">
                            <!-- Notification button -->

                            <button class="topheader-icon-btn" id="notif-btn" {{ (empty($unReadNotifications) or count($unReadNotifications) < 1) ? 'disabled' : '' }} >
                                <span class="topheader-material-symbols-outlined topheader-icon-bell-grad">notifications</span>
                                @if(!empty($unReadNotifications) and count($unReadNotifications))
                                    <div class="topheader-badge" id="notif-badge">{{ count($unReadNotifications) }}</div>
                                @endif
                            </button>

                            @if((empty($userCarts) or count($userCarts) < 1) and !empty($userCartDiscount))

                                <button class="topheader-icon-btn" id="cart-btn" >
                                    <span class="topheader-material-symbols-outlined topheader-icon-cart-grad">shopping_cart</span>
                                </button>

                            @else
                                <button class="topheader-icon-btn" id="cart-btn" {{ (empty($userCarts) or count($userCarts) < 1) ? 'disabled' : '' }} >
                                    <span class="topheader-material-symbols-outlined topheader-icon-cart-grad">shopping_cart</span>
                                    @if(!empty($userCarts) and count($userCarts))
                                        <div class="topheader-badge" id="cart-badge">{{ count($userCarts) }}</div>
                                    @endif
                                </button>
                            @endif

                            <!-- Avatar -->
                            @if(!empty($authUser))
                            <div class="topheader-avatar-small" id="user-btn">
                            <img src="{{ $authUser->getAvatar() }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}" width="50" onerror="this.src='https://placehold.co/40x40?text=K'">
                            </div>
                            @else
                            <div> <a href="/login" class="py-5 px-10 mr-10 text-dark-blue font-14">  {{ trans('auth.login') . ' / ' . trans('auth.register') }}</a></div>
                            @endif

                            <!-- NOTIFICATION POPOVER -->
                            <div class="topheader-popover" id="notif-popover">
                                <div class="topheader-popover-header">
                                    <div class="topheader-popover-header-left">
                                        <span class="topheader-material-symbols-outlined" style="font-size:18px;color:var(--chakra-gold);">auto_awesome</span>
                                        <div class="topheader-popover-title">Notifications</div>
                                    </div>
                                    <div class="topheader-popover-pill">Global ranking • Chats • Earnings</div>
                                </div>
                                @if(!empty($unReadNotifications) and count($unReadNotifications))
                                    <div class="topheader-popover-list">
                                        

                                        @foreach($unReadNotifications as $unReadNotification)
                                            <a href="/panel/notifications?notification={{ $unReadNotification->id }}">
                                                <div class="topheader-notif-item">
                                                    <div class="topheader-notif-badge topheader-chat">
                                                        <span class="topheader-material-symbols-outlined">chat_bubble</span>
                                                    </div>
                                                    <div class="topheader-notif-content">
                                                        <div class="topheader-notif-title-line">
                                                            <div class="topheader-notif-title">{{ $unReadNotification->title }}</div>
                                                            <div class="topheader-notif-time">
                                                                <!-- 10m -->
                                                            </div>
                                                        </div>
                                                        <div class="topheader-notif-text">
                                                        {{ dateTimeFormat($unReadNotification->created_at,'j M Y | H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach

                                        
                                    </div>

                                    <div class="topheader-popover-footer">
                                        <a href="/panel/notifications/mark-all-as-read" ><button class="topheader-btn-ghost" id="notif-mark-read">Mark all read</button></a>
                                        @if(!empty($unReadNotifications) and count($unReadNotifications))

                                            <a href="/panel/notifications"><button class="topheader-btn-gold">View all</button></a>
                                        @endif
                                    </div>
                                    @else
                                        <div class="topheader-popover-list">
                                            <i data-feather="bell" width="20" height="20" class="mr-10"></i>
                                            <span class="">{{ trans('notification.empty_notifications') }}</span>
                                        </div>
                                    @endif
                            </div>

                            <!-- CART POPOVER -->
                            <div class="topheader-popover" id="cart-popover">
                                <div class="topheader-popover-header">
                                    <div class="topheader-popover-header-left">
                                        <span class="topheader-material-symbols-outlined" style="font-size:18px;color:var(--chakra-blue);">shopping_cart</span>
                                        <div class="topheader-popover-title">Cart</div>
                                    </div>
                                    <div class="topheader-popover-pill">Shop • Books • Courses</div>
                                </div>
                                @if(!empty($userCarts) and count($userCarts) > 0)
                                    <div class="topheader-popover-list">
                                        <!-- Item 1 -->
                                        @foreach($userCarts as $cart)
                                            @php
                                                $cartItemInfo = $cart->getItemInfo();
                                                $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
                                            @endphp

                                            @if(!empty($cartItemInfo))
                                                <div class="topheader-cart-item">
                                                    <div class="topheader-cart-thumb">
                                                        <img src="{{ $cartItemInfo['imgPath'] }}"
                                                            alt="Book" />
                                                    </div>
                                                    <div class="topheader-cart-info">
                                                        <div class="topheader-cart-title">{{ $cartItemInfo['title'] }}</div>
                                                        <div class="topheader-cart-meta">
                                                            <!-- Digital • Instant access -->
                                                        </div>
                                                        <div class="topheader-cart-price">
                                                            @if(!empty($cartItemInfo['discountPrice']))
                                                            <span class="text-primary font-weight-bold">{{ handlePrice($cartItemInfo['discountPrice'], true, true, false, null, true, $cartTaxType) }}</span>
                                                                <span class="off ml-15">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</span>
                                                            @else
                                                                <span class="text-primary font-weight-bold">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        
                                    </div>

                                    <div class="topheader-cart-summary">
                                        <div class="topheader-cart-summary-row">
                                            <span>Subtotal</span>
                                            <span>{{ !empty($totalCartsPrice) ? handlePrice($totalCartsPrice, true, true, false, null, true, $cartTaxType) : 0 }}</span>
                                        </div>
                                        <div class="topheader-cart-summary-row">
                                            <span class="topheader-cart-total-label">Total</span>
                                            <span class="topheader-cart-total-amount">{{ !empty($totalCartsPrice) ? handlePrice($totalCartsPrice, true, true, false, null, true, $cartTaxType) : 0 }}</span>
                                        </div>
                                    </div>

                                    <div class="topheader-popover-footer">
                                        <a href="/cart/">
                                            <button class="topheader-btn-ghost">{{ trans('cart.go_to_cart') }}</button>
                                        </a>
                                        <!-- <a href="/cart/">
                                            <button class="topheader-btn-gold">Checkout</button>
                                        </a> -->
                                    </div>
                                @else
                                    <div class="topheader-popover-list">
                                        <i data-feather="shopping-cart" width="20" height="20" class="mr-10"></i>
                                        <span class="">{{ trans('cart.your_cart_empty') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- USER POPOVER -->
                            @if(!empty($authUser))
                            <div class="topheader-popover" id="user-popover">
                                <div class="topheader-user-popover-header">
                                    <div class="topheader-user-avatar-large">
                                        <img src="{{ $authUser->getAvatar() }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}" onerror="this.src='https://placehold.co/40x40?text=K'">
                                    </div>
                                    <div class="topheader-user-info">
                                        <div class="topheader-user-name">{{ $authUser->full_name }}</div>
                                        <div class="topheader-user-account-type">{{ $authUser->role->caption }}</div>
                                    </div>
                                </div>

                                <div class="topheader-popover-list">
                                    @if($authUser->isAdmin())
                                        <a href="{{ getAdminPanelUrl() }}" class="active"><div class="topheader-cart-item">
                                            <!-- <div class="topheader-cart-thumb"> -->
                                                <span class="dashboard-ms">space_dashboard</span>
                                            <!-- </div> -->
                                            <div class="topheader-cart-info">Dashboard</div>
                                        </div></a>
                                        <a href="{{ getAdminPanelUrl("/settings") }}" class="active"><div class="topheader-cart-item">
                                            <!-- <div class="topheader-cart-thumb"> -->
                                                <span class="dashboard-ms">settings</span>
                                            <!-- </div> -->
                                            <div class="topheader-cart-info">Settings</div>
                                        </div></a>
                                    @else
                                        <a href="/panel" class="active"><div class="topheader-cart-item">
                                            <!-- <div class="topheader-cart-thumb"> -->
                                                <span class="dashboard-ms">space_dashboard</span>
                                            <!-- </div> -->
                                            <div class="topheader-cart-info">Dashboard</div>
                                        </div></a>
                                        <a href="{{ $authUser->getProfileUrl() }}" class="active"><div class="topheader-cart-item">
                                            <!-- <div class="topheader-cart-thumb"> -->
                                                <span class="dashboard-ms">person</span>
                                            <!-- </div> -->
                                            <div class="topheader-cart-info">Profile</div>
                                        </div></a>
                                        <a href="/panel/notifications" class="active"><div class="topheader-cart-item">
                                            <!-- <div class="topheader-cart-thumb"> -->
                                                <span class="dashboard-ms">notifications</span>
                                            <!-- </div> -->
                                            <div class="topheader-cart-info">Notifications</div>
                                        </div></a>
                                        <a href="/panel/setting" class="active"><div class="topheader-cart-item">
                                            <!-- <div class="topheader-cart-thumb"> -->
                                                <span class="dashboard-ms">settings</span>
                                            <!-- </div> -->
                                            <div class="topheader-cart-info">Settings</div>
                                        </div></a>
                                    @endif
                                    <a href="/logout" class="active"><div class="topheader-cart-item">
                                        <!-- <div class="topheader-cart-thumb"> -->
                                            <span class="dashboard-ms">logout</span>
                                        <!-- </div> -->
                                        <div class="topheader-cart-info">Logout</div>
                                    </div></a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </header>
                    @yield('content')
                </div>
            </main>
            
        </div>

        <div id="app-popup" class="app-popup" style="display: none;">
            <div class="app-popup-content">
                <!-- <button class="app-popup-close">&times;</button> -->
                <div class="app-popup-logo">
                    @if(!empty($generalSettings['logo']))
                        <img src="{{ $generalSettings['logo'] }}" width="50" alt="site logo">
                    @endif
                </div>
                <h4>Get Our Mobile App</h4>
                <p>Learn anytime, anywhere with our app</p>
                    
                    <!-- Android Button (shown only on Android) -->
                <div id="android-container" style="display: none;">
                    <a href="#" id="android-link" class="app-store-btn android-btn" target="_blank">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.523,15.3414c-.5106-.5214-.878-1.2168-.878-1.9782s.3675-1.4568.878-1.9782l2.2675-2.3196c1.8228-1.8648.7806-5.094-1.8132-5.094H5.3702c-2.5938,0-3.636,3.2292-1.8132,5.094l2.2675,2.3196c.5106.5214.878,1.2168.878,1.9782s-.3675,1.4568-.878,1.9782l-2.2675,2.3196c-1.8228,1.8648-.7806,5.094,1.8132,5.094h12.7858c2.5938,0,3.636-3.2292,1.8132-5.094l-2.2675-2.3196Zm-6.127-9.2154h1.08v1.08h-1.08v-1.08Zm1.08,11.88h-1.08v-1.08h1.08v1.08Z"/>
                        </svg>
                        <span>GET IT ON <strong>Google Play</strong></span>
                    </a>
                </div>
                    
                    <!-- iOS Button (shown only on iOS) -->
                <div id="ios-container" style="display: none;">
                    <a href="#" id="ios-link" class="app-store-btn ios-btn" target="_blank">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.05,20.28c-.98.95-2.05.88-3.08.39-1.09-.5-2.08-.52-3.23,0-1.44.65-2.2.47-3.06-.39-4.06-4.21-3.47-10.67,1.14-10.9,1.25.03,2.13.68,2.86.73.97-.19,1.9-.75,2.91-.82,1.24-.09,2.44.45,3.25,1.19-2.95,1.86-2.42,6.02.56,7.19-.48,1.14-1.07,2.26-1.95,3.01h0Zm-4.01-14.21c-.21,1.92-1.59,3.5-3.35,3.61-.21-1.33.44-2.73,1.61-3.59.29-.23.62-.4.96-.49.02-.16.03-.32.03-.49.02-.84.35-1.64.89-2.27.7-.77,1.72-1.22,2.79-1.22.11.88-.23,1.75-.79,2.42-.45.53-1.06.91-1.74,1.11.04.14.06.29.07.43.01.12.02.24.02.36l-.49.13Z"/>
                        </svg>
                        <span>Download on the <strong>App Store</strong></span>
                    </a>
                </div>
                    
                <!-- <div class="app-popup-footer">
                    <a href="#" class="app-popup-dont-show">Don't show again</a>
                </div> -->
                </div>
            </div>
            
        </div>

        <!-- Template JS File -->
        <script src="/assets/default/js/app.js"></script>
        <script src="/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
        <script src="/assets/default/vendors/moment.min.js"></script>
        <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>
        <script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @if(empty($justMobileApp) and checkShowCookieSecurityDialog())
            @include('web.default.includes.cookie-security')
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const isMobile = /iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent);
                const isAndroid = /Android/i.test(navigator.userAgent);
                const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
                const popupDontShow = localStorage.getItem('appPopupDontShow');

                // ─── DEEP LINK CONFIGURATION ──────────────────────────────────────────────
                const DEEP_LINKS = {
                    androidScheme: 'kemeticapp://home',        // Opens Home/Dashboard in Android app
                    iosScheme:     'kemeticapp://home',        // Opens Home/Dashboard in iOS app

                    // Fallback: redirect to store if app is NOT installed
                    androidStore: 'https://play.google.com/store/apps/details?id=com.app.kemeticapp&pcampaignid=web_share',
                    iosStore:     'https://apps.apple.com/in/app/kemetic-app/id6479200304',
                };
                // ──────────────────────────────────────────────────────────────────────────

                if (isMobile && !popupDontShow && (isAndroid || isIOS)) {
                    if (isAndroid) {
                        document.getElementById('android-container').style.display = 'block';
                    } else if (isIOS) {
                        document.getElementById('ios-container').style.display = 'block';
                    }

                    setTimeout(function () {
                        const popup = document.getElementById('app-popup');
                        if (popup) popup.style.display = 'flex';
                    }, 100);
                }

                // ─── DEEP LINK HANDLER ────────────────────────────────────────────────────
                function openDeepLink(schemeUrl, storeUrl) {
                    let appOpened = false;

                    // Cancel fallback if app launches (page loses focus)
                    function cancelFallback() {
                        appOpened = true;
                        clearTimeout(fallbackTimer);
                    }

                    window.addEventListener('pagehide', cancelFallback, { once: true });
                    window.addEventListener('blur', cancelFallback, { once: true });
                    document.addEventListener('visibilitychange', function onVisChange() {
                        if (document.hidden) {
                            appOpened = true;
                            clearTimeout(fallbackTimer);
                            document.removeEventListener('visibilitychange', onVisChange);
                        }
                    });

                    // Step 1: Try opening app via deep link scheme
                    window.location.href = schemeUrl;

                    // Step 2: If app not installed, redirect to store after 1.5s
                    var fallbackTimer = setTimeout(function () {
                        if (!appOpened) {
                            window.location.href = storeUrl;
                        }
                    }, 100);
                }
                // ──────────────────────────────────────────────────────────────────────────

                // Android button → deep link
                const androidLink = document.getElementById('android-link');
                if (androidLink) {
                    androidLink.href = '#';
                    androidLink.addEventListener('click', function (e) {
                        e.preventDefault();
                        openDeepLink(DEEP_LINKS.androidScheme, DEEP_LINKS.androidStore);
                    });
                }

                // iOS button → deep link
                const iosLink = document.getElementById('ios-link');
                if (iosLink) {
                    iosLink.href = '#';
                    iosLink.addEventListener('click', function (e) {
                        e.preventDefault();
                        openDeepLink(DEEP_LINKS.iosScheme, DEEP_LINKS.iosStore);
                    });
                }

                // Don't show again
                // const dontShowBtn = document.querySelector('.app-popup-dont-show');
                // if (dontShowBtn) {
                //     dontShowBtn.addEventListener('click', function (e) {
                //         e.preventDefault();
                //         document.getElementById('app-popup').style.display = 'none';
                //         localStorage.setItem('appPopupDontShow', 'true');
                //     });
                // }

            });

        </script>
        <script>
        const notifBtn = document.getElementById('notif-btn');
        const cartBtn = document.getElementById('cart-btn');
        const userBtn = document.getElementById('user-btn');
        const notifPopover = document.getElementById('notif-popover');
        const cartPopover = document.getElementById('cart-popover');
        const userPopover = document.getElementById('user-popover');
        const notifBadge = document.getElementById('notif-badge');
        const markReadBtn = document.getElementById('notif-mark-read');

        function closeAllPopovers() {
            notifPopover.classList.remove('topheader-visible');
            cartPopover.classList.remove('topheader-visible');
            userPopover.classList.remove('topheader-visible');
        }

        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isVisible = notifPopover.classList.contains('topheader-visible');
            closeAllPopovers();
            if (!isVisible) notifPopover.classList.add('topheader-visible');
        });

        cartBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isVisible = cartPopover.classList.contains('topheader-visible');
            closeAllPopovers();
            if (!isVisible) cartPopover.classList.add('topheader-visible');
        });

        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isVisible = userPopover.classList.contains('topheader-visible');
            closeAllPopovers();
            if (!isVisible) userPopover.classList.add('topheader-visible');
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.topheader-popover') &&
                !e.target.closest('#notif-btn') &&
                !e.target.closest('#cart-btn') && 
                !e.target.closest('#user-btn')) {
            closeAllPopovers();
            }
        });

        // “Mark all read” demo – just clears badge
        //   markReadBtn.addEventListener('click', () => {
        //     notifBadge.textContent = '';
        //     notifBadge.style.display = 'none';
        //   });
        </script>

        <script>

            const menu = document.getElementById('menu');
            const openBtn = document.getElementById('openBtn');
            const closeBtn = document.getElementById('closeBtn');
            const backdrop = document.getElementById('backdrop');

            function openMenu()
            {
                menu.classList.add('open');
                backdrop.classList.add('show');
            }
            function closeMenu()
            {
                menu.classList.remove('open');
                backdrop.classList.remove('show');
            }
            if (openBtn) openBtn.addEventListener('click', openMenu);
            if (closeBtn) closeBtn.addEventListener('click', closeMenu);
            if (backdrop) backdrop.addEventListener('click', closeMenu);

            // Optional: wire clicks to routes
            document.querySelectorAll('.header-tile').forEach(tile=>{
                tile.addEventListener('click', () => {
                // TODO: handle navigation (window.location, router push, etc.)
                closeMenu();
                });
            });

            var deleteAlertTitle = '{{ trans('public.are_you_sure') }}';
            var deleteAlertHint = '{{ trans('public.deleteAlertHint') }}';
            var deleteAlertConfirm = '{{ trans('public.deleteAlertConfirm') }}';
            var deleteAlertCancel = '{{ trans('public.cancel') }}';
            var deleteAlertSuccess = '{{ trans('public.success') }}';
            var deleteAlertFail = '{{ trans('public.fail') }}';
            var deleteAlertFailHint = '{{ trans('public.deleteAlertFailHint') }}';
            var deleteAlertSuccessHint = '{{ trans('public.deleteAlertSuccessHint') }}';
            var forbiddenRequestToastTitleLang = '{{ trans('public.forbidden_request_toast_lang') }}';
            var forbiddenRequestToastMsgLang = '{{ trans('public.forbidden_request_toast_msg_lang') }}';
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

    </body>
</html>