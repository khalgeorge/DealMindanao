<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connecting to Facebook…</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
               display: flex; align-items: center; justify-content: center;
               min-height: 100vh; margin: 0; background: #f0f4ff; }
        .box { background: #fff; border-radius: 12px; padding: 32px 28px;
               box-shadow: 0 4px 24px rgba(0,0,0,.12); text-align: center; max-width: 320px; }
        .spinner { width: 36px; height: 36px; border: 3px solid #e2e8f0;
                   border-top-color: #1877f2; border-radius: 50%;
                   animation: spin .7s linear infinite; margin: 0 auto 16px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        p { color: #475569; font-size: 14px; margin: 0 0 8px; line-height: 1.5; }
        .error { color: #dc2626; background: #fef2f2; border: 1px solid #fecaca;
                 border-radius: 8px; padding: 12px; font-size: 13px; }
        .success { color: #15803d; font-weight: 600; }
    </style>
</head>
<body>
<div class="box" id="box">
@if(isset($error))
    <p class="error">{{ $error }}</p>
    <p style="margin-top:12px;font-size:13px;color:#94a3b8">You can close this window and try again.</p>
    <script>
    (function(){
        try {
            window.opener && window.opener.postMessage(
                { type: 'dm_fb_auth_error', error: @json($error) },
                window.location.origin
            );
        } catch(e){}
        setTimeout(function(){ try{ window.close(); }catch(e){} }, 3000);
    })();
    </script>
@else
    <div class="spinner"></div>
    <p>Connecting with Facebook&hellip;</p>
    <script>
    (function(){
        var guestToken = @json($guestToken);
        var name       = @json($name);
        var origin     = window.location.origin;
        try { localStorage.setItem('dm_guest_token', guestToken); } catch(e){}
        try { localStorage.setItem('dm_guest_name',  name);       } catch(e){}
        try {
            window.opener && window.opener.postMessage(
                { type: 'dm_fb_auth', guestToken: guestToken, name: name },
                origin
            );
        } catch(e){}
        document.getElementById('box').innerHTML =
            '<svg width="40" height="40" viewBox="0 0 24 24" fill="#16a34a" style="margin-bottom:12px"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' +
            '<p class="success">Connected as ' + name + '!</p>' +
            '<p style="font-size:13px;color:#94a3b8">Closing window&hellip;</p>';
        setTimeout(function(){ try{ window.close(); }catch(e){} }, 800);
    })();
    </script>
@endif
</div>
</body>
</html>
