@php
    $pixelSettings = \App\Models\MarketingSetting::getSettings();
@endphp

@if ($pixelSettings)
    {{-- Google Analytics (GA4) --}}
    @if ($pixelSettings->google_analytics_enabled && $pixelSettings->google_analytics_id)
        <!-- Google tag (gtag.js) GA4 -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $pixelSettings->google_analytics_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $pixelSettings->google_analytics_id }}');
        </script>
    @endif

    {{-- Google Ads Tracking --}}
    @if ($pixelSettings->google_ads_enabled && $pixelSettings->google_ads_id)
        <!-- Google Ads Conversion Tag -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $pixelSettings->google_ads_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $pixelSettings->google_ads_id }}');
        </script>
    @endif

    {{-- Meta (Facebook) Pixel --}}
    @if ($pixelSettings->meta_pixel_enabled && $pixelSettings->meta_pixel_id)
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
            fbq('init', '{{ $pixelSettings->meta_pixel_id }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $pixelSettings->meta_pixel_id }}&ev=PageView&noscript=1"/>
        </noscript>
    @endif

    {{-- TikTok Pixel --}}
    @if ($pixelSettings->tiktok_pixel_enabled && $pixelSettings->tiktok_pixel_id)
        <!-- TikTok Pixel Code -->
        <script>
            !function (w, d, t) {
                w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=r+"?sdkid="+e+"&lib="+t;var c=document.getElementsByTagName("script")[0];c.parentNode.insertBefore(a,c)};
                ttq.load('{{ $pixelSettings->tiktok_pixel_id }}');
                ttq.page();
            }(window, document, 'ttq');
        </script>
    @endif
@endif
