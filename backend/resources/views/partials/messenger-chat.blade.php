{{-- Facebook Customer Chat Plugin --}}
{{-- Requires Facebook login — messages go directly to the FB Page inbox --}}
@php
    $fbPageId = config('services.facebook.page_id');
    $fbAppId  = config('services.facebook.app_id');
@endphp

<div id="fb-root"></div>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId   : '{{ $fbAppId }}',
      xfbml   : true,
      version : 'v22.0'
    });
  };

  (function(d, s, id) {
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
  theme_color="#0084ff"
  logged_in_greeting="Hi! How can we help you today? ð"
  logged_out_greeting="Hi! Log in to Facebook to send us a message.">
</div>
