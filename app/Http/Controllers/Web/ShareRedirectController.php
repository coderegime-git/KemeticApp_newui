<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShareRedirectController extends Controller
{
    public function launch(Request $request)
    {
        $page   = $request->query('page', 'home');
        $value  = $request->query('value', '');
        $scheme = 'kemeticapp';

        $deeplink = match($page) {
            'home'  => "{$scheme}://home",
            default => "{$scheme}://{$page}/{$value}",
        };

        $androidStore = 'https://play.google.com/store/apps/details?id=com.app.kemeticapp&pcampaignid=web_share';
        $iosStore     = 'https://apps.apple.com/in/app/kemetic-app/id6479200304';
        $webUrl       = 'https://dev.kemetic.app';

        // Detect device server-side
        $userAgent = $request->header('User-Agent', '');
        $isAndroid = stripos($userAgent, 'android') !== false;
        $isIOS     = (bool) preg_match('/(iPhone|iPad|iPod)/i', $userAgent);

        // Desktop → go to website
        if (!$isAndroid && !$isIOS) {
            return redirect($webUrl);
        }

        $storeUrl = $isAndroid ? $androidStore : $iosStore;

        // Mobile → silent redirect page (no visible UI)
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Redirecting...</title>
    <style>
        body { margin:0; background:#000; }
    </style>
</head>
<body>
<script>
(function () {
    var deeplink = "{$deeplink}";
    var storeUrl = "{$storeUrl}";
    var appOpened = false;

    function cancelFallback() {
        appOpened = true;
        clearTimeout(fallbackTimer);
    }

    window.addEventListener('pagehide', cancelFallback, { once: true });
    window.addEventListener('blur',     cancelFallback, { once: true });
    document.addEventListener('visibilitychange', function onVisChange() {
        if (document.hidden) {
            appOpened = true;
            clearTimeout(fallbackTimer);
            document.removeEventListener('visibilitychange', onVisChange);
        }
    });

    // Try to open app
    window.location.href = deeplink;

    // If app not installed → go to store after 1500ms
    var fallbackTimer = setTimeout(function () {
        if (!appOpened) {
            window.location.href = storeUrl;
        }
    }, 100);
})();
</script>
</body>
</html>
HTML;

        return response($html, 200)->header('Content-Type', 'text/html');
    }
}