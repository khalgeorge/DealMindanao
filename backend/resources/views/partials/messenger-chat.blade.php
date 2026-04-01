{{--
    Facebook Customer Chat Plugin - public pages only
    Embeds Messenger chat inline on the page (no redirect).
    Requires APP_ENV=production, FACEBOOK_APP_ID, and FACEBOOK_PAGE_ID.
--}}
@php
    $fbAppId  = config('services.facebook.app_id', '');
    $fbPageId = config('services.facebook.page_id', '');
@endphp

@if(app()->environment('production') && $fbAppId && $fbPageId)
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function () {
        FB.init({
            appId: '{{ $fbAppId }}',
            xfbml: true,
            version: 'v22.0'
        });
    };
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<div class="fb-customerchat"
     attribution="setup_tool"
     page_id="{{ $fbPageId }}"
     greeting_dialog_display="hide"
     greeting_dialog_delay="3"
     logged_in_greeting="Hi! How can we help you today?"
     logged_out_greeting="Hi! Chat with us on Messenger.">
</div>
@endif
