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