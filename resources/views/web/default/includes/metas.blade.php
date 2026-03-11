<meta charset="utf-8">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<meta name="robots" content="{{ $pageRobot ?? 'index, follow' }}">

<link rel="canonical" href="{{ url()->current() }}" />
<meta name="google-site-verification"
      content="K3FxvckIZ41vklr31BJOknd5wQxHQA_EsxAwwB_BDZE" />

@if (isset($pageDescription) and !empty($pageDescription))
    <meta name="description" content="{{ $pageDescription }}">
    <meta property="og:description" content="{{ (!empty($ogDescription)) ? $ogDescription : $pageDescription }}">
    <meta name='twitter:description' content='{{ (!empty($ogDescription)) ? $ogDescription : $pageDescription }}'>
@endif

<link rel='shortcut icon' type='image/x-icon' href="{{ url(!empty($generalSettings['fav_icon']) ? $generalSettings['fav_icon'] : '') }}">
<link rel="manifest" href="/mix-manifest.json?v=4">
<meta name="theme-color" content="#FFF">
<!-- Windows Phone -->
<meta name="msapplication-starturl" content="/">
<meta name="msapplication-TileColor" content="#FFF">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<!-- iOS Safari -->
<meta name="apple-mobile-web-app-title" content="{{ !empty($generalSettings['site_name']) ? $generalSettings['site_name'] : '' }}">
<link rel="apple-touch-icon" href="{{ url(!empty($generalSettings['fav_icon']) ? $generalSettings['fav_icon'] : '') }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<!-- Android -->
<link rel='icon' href='{{ url(!empty($generalSettings['fav_icon']) ? $generalSettings['fav_icon'] : '') }}'>
<meta name="application-name" content="{{ !empty($generalSettings['site_name']) ? $generalSettings['site_name'] : '' }}">
<meta name="mobile-web-app-capable" content="yes">
<!-- Other -->
<meta name="layoutmode" content="fitscreen/standard">
<link rel="home" href="{{ url('') }}">

<!-- Open Graph -->
<meta property='og:title' content='{{ $pageTitle ?? '' }}'>
<meta name='twitter:card' content='summary'>
<meta name='twitter:title' content='{{ $pageTitle ?? '' }}'>

@php
    if (empty($pageMetaImage)) {
        $pageMetaImage = !empty($generalSettings['fav_icon']) ? $generalSettings['fav_icon'] : '/';
    }
@endphp

<meta property="og:site_name" content="{{ $generalSettings['site_name'] ?? 'Kemetic App' }}">
<meta property='og:image' content='{{ url($pageMetaImage) }}'>
<meta name='twitter:image' content='{{ url($pageMetaImage) }}'>
<meta property='og:locale' content='{{ url(!empty($generalSettings['locale']) ? $generalSettings['locale'] : 'en_US') }}'>
<meta property='og:type' content='website'>


<script async src="https://www.googletagmanager.com/gtag/js?id=G-NN39N0EP49"></script>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){ dataLayer.push(arguments); }
  gtag('js', new Date());
  gtag('config', 'G-NN39N0EP49');
</script>

<!-- Meta Pixel -->
<script>
  !function(f,b,e,v,n,t,s)...
  fbq('init', '878039774311472');
  fbq('track', 'PageView');
</script>

<!-- GetResponse Web Connect -->
<script type="text/javascript">
    (function(m, o, n, t, e, r, _){
        m['__GetResponseAnalyticsObject'] = e;
        m[e] = m[e] || function() {(m[e].q = m[e].q || []).push(arguments)};
        r = o.createElement(n);
        _ = o.getElementsByTagName(n)[0];
        r.async = 1;
        r.src = t;
        r.setAttribute('crossorigin', 'use-credentials');
        _.parentNode.insertBefore(r, _);
    })(window, document, 'script', 'https://an.gr-wcon.com/script/1cdaabfc-c3d4-4352-b44d-19a6253fb7c9/ga.js', 'GrTracking');
</script>

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
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;
j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXXX');</script>

<!-- <script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];
  ttq.methods=["page","track","identify"];
  ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};
  for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);
  ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";
  ttq._i=ttq._i||{},ttq._i[e]=[],ttq._t=ttq._t||{},ttq._t[e]=+new Date;
  var o=document.createElement("script");o.type="text/javascript";o.async=!0;
  o.src=i+"?sdkid="+e;var a=document.getElementsByTagName("script")[0];
  a.parentNode.insertBefore(o,a)};
  ttq.load('YOUR_TIKTOK_PIXEL_ID');  // ← replace this
  ttq.page();
}(window, document, 'ttq');
</script>

<script>
(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],
f=function(){var o={ti:"YOUR_UET_TAG_ID",enableAutoSpaTracking:true};  // ← replace
o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),
n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){
var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),
n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],
i.parentNode.insertBefore(n,i)})
(window,document,"script","//bat.bing.com/bat.js","uetq");
</script>
<script>
!function(e){if(!window.pintrk){window.pintrk=function(){window.pintrk.queue.push(
Array.prototype.slice.call(arguments))};var n=window.pintrk;
n.queue=[],n.version="3.0";var t=document.createElement("script");
t.async=!0,t.src=e;var r=document.getElementsByTagName("script")[0];
r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");
pintrk('load', 'YOUR_PINTEREST_TAG_ID');  // ← replace this
pintrk('page');
</script> -->

<script>
  fbq('init', '878039774311472');
  fbq('track', 'PageView');
</script>


{!! getSeoMetas('extra_meta_tags') !!}
<!-- SCHEMA.ORG JSON-LD MARKUP -->
@include('web.default.includes.schema-org')

