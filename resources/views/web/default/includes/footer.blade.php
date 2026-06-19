@php
    $socials = getSocials();
    if (!empty($socials) and count($socials)) {
        $socials = collect($socials)->sortBy('order')->toArray();
    }

    $footerColumns = getFooterColumns();
@endphp


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
        <p>We use cookies to improve your experience. By continuing to use this site, you accept our <a
                class="cookie-policy" href="{{url('pages/privacy-policy')}}">Cookie Policy</a>.</p>
        <button id="accept-cookies">Accept Cookies</button>
    </div>
</div>

<style>
    /* Kemetic Custom Footer */
    .kemetic-custom-footer {
        background: linear-gradient(180deg, #0b0b0b, #121212) !important;
        border-top: 1px solid rgba(242, 201, 76, 0.25);
        padding: 60px 0 30px;
        color: #fff;
        position: relative;
        z-index: 10;
    }

    .kemetic-custom-footer .footer-col-title {
        color: #f2c94c !important;
        font-weight: 700 !important;
        margin-bottom: 25px !important;
        font-size: 16px !important;
        text-transform: uppercase !important;
        display: block !important;
        letter-spacing: 0.5px;
    }

    .kemetic-custom-footer .footer-list {
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .kemetic-custom-footer .footer-list li {
        margin-bottom: 12px !important;
        display: flex;
    }

    .kemetic-custom-footer .kemetic-footer-link {
        color: #c9b26d !important;
        text-decoration: none !important;
        font-size: 14px !important;
        transition: 0.3s ease-in-out;
    }

    .kemetic-custom-footer .kemetic-footer-link:hover {
        color: #f2c94c !important;
        padding-left: 5px;
    }

    .kemetic-custom-footer .footer-text {
        color: #c9b26d !important;
        font-size: 14px !important;
        margin-bottom: 10px !important;
        line-height: 1.6 !important;
    }

    .kemetic-custom-footer .footer-subscribe {
        background: transparent;
    }

    .kemetic-custom-footer strong {
        color: #f2c94c !important;
    }

    /* Custom scrollbar for Consents list */
    .kemetic-consent-scroll {
        max-height: 480px;
        overflow-y: auto;
        padding-right: 10px !important;
        scrollbar-width: thin;
        scrollbar-color: #f2c94c #1e1e1e;
    }

    .kemetic-consent-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .kemetic-consent-scroll::-webkit-scrollbar-track {
        background: #1e1e1e;
        border-radius: 10px;
    }

    .kemetic-consent-scroll::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #f2c94c, #c9b26d);
        border-radius: 10px;
    }

    .kemetic-consent-scroll::-webkit-scrollbar-thumb:hover {
        background: #f2c94c;
    }

    /* Newsletter block */
    .footer-newsletter {
        margin-top: 20px;
    }

    .footer-newsletter p {
        color: #c9b26d;
        font-size: 13px;
        margin-bottom: 12px;
        line-height: 1.5;
    }

    .footer-newsletter .newsletter-btn {
        display: inline-block;
        background: linear-gradient(135deg, #f2c94c, #c9a227);
        color: #0b0b0b !important;
        font-weight: 700;
        font-size: 13px;
        padding: 9px 20px;
        border-radius: 4px;
        text-decoration: none !important;
        letter-spacing: 0.4px;
        transition: 0.3s ease;
    }

    .footer-newsletter .newsletter-btn:hover {
        background: linear-gradient(135deg, #ffe47a, #f2c94c);
        color: #0b0b0b !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(242, 201, 76, 0.3);
    }
</style>

<footer class="footer kemetic-custom-footer position-relative user-select-none">

    <div class="container mt-40">
        <div class="row">

            <!-- Company Info -->
            <div class="col-12 col-md-6 col-lg-3 mb-5 mb-lg-0">
                <h4 class="footer-col-title">Kemetic App</h4>

                <div class="footer-text">
                    <strong>Chamber of commerce registration:</strong><br>
                    BLACKBEACON B.V. - 97514047
                </div>

                <div class="footer-text mt-3">
                    <strong>Address:</strong><br>
                    Haverdreef 37, 3204GD Spijkenisse
                </div>

                <ul class="footer-list mt-4">
                    <li><a href="https://www.kemetic.app" class="kemetic-footer-link">www.kemetic.app</a></li>
                </ul>

                <div class="footer-social mt-4">
                    @if(!empty($socials) and count($socials))
                        @foreach($socials as $social)
                            <a href="{{ $social['link'] }}" target="_blank" class="mr-3">
                                <img src="{{ $social['image'] }}" alt="{{ $social['title'] }}" width="24">
                            </a>
                        @endforeach
                    @endif
                </div>

                <!-- Newsletter -->
                <!-- <div class="footer-newsletter">
                    <span class="footer-col-title" style="margin-top: 25px;">Newsletter</span>
                    <p>Stay updated with the latest wisdom, features & news from Kemetic App.</p>
                    <a href="/pages/newsletter" class="newsletter-btn">&#9993; Subscribe Now</a>
                </div> -->
            </div>

            <!-- About -->
            <div class="col-12 col-md-6 col-lg-2 mb-5 mb-lg-0">
                <h4 class="footer-col-title">About</h4>
                <ul class="footer-list">
                    <li><a href="/pages/about" class="kemetic-footer-link">About us</a></li>
                    <li><a href="/pages/founder" class="kemetic-footer-link">Founder</a></li>
                    <li><a href="/pages/team" class="kemetic-footer-link">Team</a></li>
                    <li><a href="/pages/partners" class="kemetic-footer-link">Partners</a></li>
                    <li><a href="/pages/invest" class="kemetic-footer-link">Invest</a></li>
                    <li><a href="/pages/crowdfunding" class="kemetic-footer-link">Crowdfunding</a></li>
                    <li><a href="/pages/reviews" class="kemetic-footer-link">Reviews</a></li>
                    <li><a href="/pages/roadmap" class="kemetic-footer-link">Roadmap</a></li>
                    <li><a href="#" class="kemetic-footer-link">Sitemap</a></li>
                    <li><a href="/pages/newsletter" class="kemetic-footer-link">Newsletter</a></li>
                </ul>
            </div>

            <!-- How it works -->
            <div class="col-12 col-md-6 col-lg-2 mb-5 mb-lg-0">
                <h4 class="footer-col-title">How It Works</h4>
                <ul class="footer-list">
                    <li><a href="/pages/How-Kemetic-App-works" class="kemetic-footer-link">Learn How Kemetic App Works</a></li>
                    <li><a href="/pages/how-it-works-seekers" class="kemetic-footer-link">Students - Seekers</a></li>
                    <li><a href="/pages/How-It-Works-Wisdom-Keepers" class="kemetic-footer-link">Content creators /
                            Partners</a></li>
                    <li><a href="/pages/Kemetic-App" class="kemetic-footer-link">Home Page</a></li>
                    <li><a href="/pages/portals-how-it-works" class="kemetic-footer-link">Shorts - Portals</a></li>
                    <li><a href="/pages/How-It-Works-Courses" class="kemetic-footer-link">Courses - Education center</a>
                    </li>
                    <li><a href="/pages/Scrolls-Book-Library" class="kemetic-footer-link">Book Library - Scrolls</a>
                    </li>
                    <li><a href="/pages/Articles-Blog-How-it-works" class="kemetic-footer-link">Blog - Articles</a></li>
                    <li><a href="/pages/How-It-Works-Messenger" class="kemetic-footer-link">Messenger</a></li>
                    <li><a href="/pages/How-It-Works-your-profile-your-website" class="kemetic-footer-link">Profile /
                            Your website</a></li>
                    <li><a href="/pages/Dropshipping-How-it-Works" class="kemetic-footer-link">Dropshipping</a></li>
                    <li><a href="/pages/Membership-How-it-works" class="kemetic-footer-link">Membership</a></li>
                    <li><a href="/pages/Order-trackingcodes" class="kemetic-footer-link">Orders and Tracking codes</a></li>
                    <li><a href="/pages/ads" class="kemetic-footer-link">Ads</a></li>
                </ul>
            </div>

            <!-- Partners -->
            <div class="col-12 col-md-6 col-lg-2 mb-5 mb-lg-0">
                <h4 class="footer-col-title">Partners</h4>
                <ul class="footer-list">
                    <li><a href="/pages/Vendor-Open-Your-Shop-Policy" class="kemetic-footer-link">Vendor - open your shop</a></li>
                    <li><a href="/pages/commission-policy" class="kemetic-footer-link">Commission policy</a></li>
                    <li><a href="/pages/Content-Creator-Policy" class="kemetic-footer-link">Content Creators</a></li>
                    <li><a href="/pages/Teacher-Course-Creator-Policy" class="kemetic-footer-link">Teacher - publish your course</a></li>
                    <li><a href="/pages/Affiliate-Program-Policy" class="kemetic-footer-link">Affiliate program</a></li>
                    <li><a href="/pages/Publisher-Book-Policy" class="kemetic-footer-link">Publisher - publish your book</a></li>
                    <li><a href="/pages/Blogger-Articles-Partner-Policy" class="kemetic-footer-link">Blogger - publish articles</a></li>
                    <li><a href="/pages/Your-Profile-Your-Website-Policy" class="kemetic-footer-link">Your profile - Your website</a></li>
                    <li><a href="/pages/Videos-Monetization-Policy" class="kemetic-footer-link">Videos Monetization</a></li>
                    <li><a href="/pages/Articles-Monetization-Policy" class="kemetic-footer-link">Articles monetization</a></li>
                    <li><a href="/pages/Droppshipping-shop-Policy" class="kemetic-footer-link">Dropshipping shop</a></li>
                    <li><a href="/pages/Promotion-Policy" class="kemetic-footer-link">Promotion</a></li>
                    <li><a href="/pages/Get-Paid-Withdrawal-Policy" class="kemetic-footer-link">Get paid - Withdrawal</a></li>
                    <li><a href="/pages/Add-Bank-Account-Policy" class="kemetic-footer-link">Add Bank account</a></li>
                    <li><a href="/pages/Stripe-Express-Policy" class="kemetic-footer-link">Stripe express</a></li>
                    <li><a href="/pages/Virtual-Kemetic-App-Visa-Card-Policy" class="kemetic-footer-link">Virtual Kemetic App Visa card</a></li>
                    <li><a href="/panel/setting" class="kemetic-footer-link">Settings</a></li>
                    <li><a href="/panel" class="kemetic-footer-link">Dashboard</a></li>
                </ul>
            </div>

            <!-- Consents -->
            <div class="col-12 col-lg-3 mb-5 mb-lg-0">
                <h4 class="footer-col-title">Consents Pages</h4>
                <ul class="footer-list kemetic-consent-scroll">
                    <li><a href="/pages/Terms-and-Conditions" class="kemetic-footer-link">Terms and Conditions</a></li>
                    <li><a href="/pages/privacy-policy" class="kemetic-footer-link">Privacy policy</a></li>
                    <li><a href="/pages/Seekers-Terms" class="kemetic-footer-link">Seekers</a></li>
                    <li><a href="/pages/Wisdom-Keepers-Terms" class="kemetic-footer-link">Wisdom keepers</a></li>
                    <li><a href="/pages/eula" class="kemetic-footer-link">Eula</a></li>
                    <li><a href="/pages/refund-policy" class="kemetic-footer-link">Return policy</a></li>
                    <li><a href="/pages/Payment-Information" class="kemetic-footer-link">Payments</a></li>
                    <li><a href="/pages/Cookies-Policy" class="kemetic-footer-link">Cookies</a></li>
                    <li><a href="/pages/Sellers-Vendors-Terms-and-Conditions"
                            class="kemetic-footer-link">Sellers/vendors T&C</a></li>
                    <li><a href="/pages/Book-Publishing-Terms-and-Conditions" class="kemetic-footer-link">Book
                            publishing T&C</a></li>
                    <li><a href="/pages/Portals-Terms-and-Conditions" class="kemetic-footer-link">Portals (Shorts)
                            T&C</a></li>
                    <li><a href="/pages/Courses-Terms-and-Conditions" class="kemetic-footer-link">Courses T&C</a></li>
                    <li><a href="/pages/Blogger-Articles-Terms-and-Conditions"
                            class="kemetic-footer-link">Blogger/articles T&C</a></li>
                    <li><a href="/pages/Dropshipping-Terms-and-Conditions" class="kemetic-footer-link">Dropshipping
                            T&C</a></li>
                    <li><a href="/pages/Shop-Terms-and-Conditions" class="kemetic-footer-link">Shop T&C</a></li>
                    <li><a href="/pages/Messenger-Terms-and-Conditions" class="kemetic-footer-link">Messenger T&C</a>
                    </li>
                    <li><a href="/pages/Your-Profile-Your-Website-Terms-and-Conditions" class="kemetic-footer-link">Your
                            profile your website T&C</a></li>
                    <li><a href="/pages/Membership-Policy" class="kemetic-footer-link">Membership policy</a></li>
                    <li><a href="/pages/Spam-policy" class="kemetic-footer-link">Spam policy</a></li>
                    <li><a href="/pages/External-Linking-policy" class="kemetic-footer-link">External Linking</a></li>
                    <li><a href="/pages/Forbidden-Not-Allowed-Policy" class="kemetic-footer-link">Forbidden - Not
                            allowed policy</a></li>
                    <li><a href="/pages/Account-Ban-Policy" class="kemetic-footer-link">Account ban policy</a></li>
                    <li><a href="/pages/Ads-Policy" class="kemetic-footer-link">Ads policy</a></li>
                    <li><a href="/pages/Monetization-terms-and-conditions" class="kemetic-footer-link">Monetization
                            T&C</a></li>
                    <li><a href="/pages/Shared-Email-list-policy" class="kemetic-footer-link">Shared Email list policy</a></li>
                </ul>
            </div>

        </div>


    </div>

    @if(getOthersPersonalizationSettings('platform_phone_and_email_position') == 'footer')
        <div class="footer-copyright-card">
            <div class="align-items-center container d-flex flex-column flex-md-row justify-content-between py-15 gap10">
                <div class="font-14 text-white">{{ trans('update.platform_copyright_hint') }}</div>

                <div class="d-flex align-items-center justify-content-center">
                    @if(!empty($generalSettings['site_phone']))
                        <div class="d-flex align-items-center text-white font-14">
                            <i data-feather="phone" width="20" height="20" class="mr-10"></i>
                            {{ $generalSettings['site_phone'] }}
                        </div>
                    @endif

                    @if(!empty($generalSettings['site_email']))
                        <div class="border-left mx-5 mx-lg-15 h-100"></div>

                        <div class="d-flex align-items-center text-white font-14">
                            <!-- <i data-feather="mail" width="20" height="20" class="mr-10"></i> -->
                           
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var isLoggedIn = @json(Auth::check()); // Check if the user is logged in

        document.querySelectorAll(".footer-link a").forEach(function (link) {
            if (link.textContent.trim() === "- Media kit Affiliate partners") {
                link.addEventListener("click", function (event) {
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
    $(document).ready(function () {
        // Check if the cookie consent is already given
        if (!getCookie('cookiesAccepted')) {
            $('#cookie-consent-banner').show();
        }

        // Accept cookies and set a cookie
        $('#accept-cookies').click(function () {
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
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }


</script>