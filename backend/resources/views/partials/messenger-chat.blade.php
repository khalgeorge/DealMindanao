{{--
    Facebook Messenger Customer Chat Plugin — public pages only
    ──────────────────────────────────────────────────────────
    • Only rendered when APP_ENV=production AND FACEBOOK_PAGE_ID is set.
    • SDK is loaded lazily after the page 'load' event (non-blocking).
    • fb-customerchat is rendered by the SDK at the bottom-right;
      it does not interfere with site layout or Tailwind classes.

    Setup:
      Add to backend/.env:
        FACEBOOK_PAGE_ID=<your numeric Facebook Page ID>

    Finding your numeric Page ID:
      facebook.com/dealmindanao → About → Page transparency → Page ID
--}}
@php
    $fbPageId = env('FACEBOOK_PAGE_ID', '');
@endphp

@if(app()->environment('production') && $fbPageId)
    {{-- FB SDK anchor element (required by the SDK) --}}
    <div id="fb-root"
         role="complementary"
         aria-label="Chat with us on Messenger"></div>

    {{-- Customer Chat plugin — SDK scans for this and renders the bubble --}}
    <div class="fb-customerchat"
         attribution="biz_inbox"
         page_id="{{ $fbPageId }}"
         logged_in_greeting="Hi! Need help with your order or delivery in Mindanao?"
         logged_out_greeting="Hi! Need help with your order or delivery in Mindanao?"
         aria-label="Chat with us on Messenger"></div>

    <script>
    /**
     * Lazy-load the Facebook Messenger Customer Chat SDK.
     * Uses window.addEventListener('load') so it never blocks page render.
     */
    window.addEventListener('load', function () {
        // fbAsyncInit must be assigned before the <script> tag is inserted
        window.fbAsyncInit = function () {
            FB.init({ xfbml: true, version: 'v19.0' });
        };

        (function (d, s, id) {
            if (d.getElementById(id)) return;
            var js = d.createElement(s);
            js.id           = id;
            js.src          = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            js.async        = true;
            js.defer        = true;
            js.crossOrigin  = 'anonymous';
            var fjs = d.getElementsByTagName(s)[0];
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    }, { once: true });
    </script>
@endif
