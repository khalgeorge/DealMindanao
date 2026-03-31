{{--
    Facebook Messenger Customer Chat Plugin — public pages only
    ──────────────────────────────────────────────────────────
    • Only rendered when APP_ENV=production AND FACEBOOK_PAGE_ID is set.
    • Domain must be whitelisted in Facebook Page Settings → Advanced Messaging.
--}}
@php
    $fbPageId = config('services.facebook.page_id', '');
@endphp

@if(app()->environment('production') && $fbPageId)
    <div id="fb-root"></div>

    <div class="fb-customerchat"
         attribution="setup_tool"
         page_id="{{ $fbPageId }}"
         theme_color="#16a34a"
         logged_in_greeting="Hi! Need help with your order or delivery in Mindanao?"
         logged_out_greeting="Hi! Need help with your order or delivery in Mindanao?">
    </div>

    <script>
        window.fbAsyncInit = function() {
            FB.init({ xfbml: true, version: 'v19.0' });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
@endif
