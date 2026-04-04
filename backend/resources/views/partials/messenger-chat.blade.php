{{-- Custom Chat Widget with Facebook OAuth popup for guests --}}
@php
    $fbPageId = config('services.facebook.page_id');
@endphp

<style>
/* ── Bubble ─────────────────────────────────────────── */
#dm-chat-bubble {
    position: fixed; bottom: 24px; right: 24px; z-index: 9998;
    height: 52px; border-radius: 999px;
    background: linear-gradient(135deg, #0084ff, #00c6ff);
    box-shadow: 0 4px 16px rgba(0,132,255,.45);
    display: inline-flex; align-items: center; gap: 8px;
    padding: 0 18px 0 14px; cursor: pointer; border: none;
    transition: transform .2s ease, box-shadow .2s ease;
    font-family: inherit;
}
#dm-chat-bubble:hover { transform: scale(1.05); box-shadow: 0 6px 24px rgba(0,132,255,.55); }
#dm-chat-bubble svg  { width: 26px; height: 26px; fill: #fff; flex-shrink: 0; }
#dm-chat-bubble span { color: #fff; font-size: 13px; font-weight: 700; white-space: nowrap; }
#dm-chat-unread {
    position: absolute; top: -4px; right: -4px;
    background: #ef4444; color: #fff; font-size: 10px; font-weight: 700;
    width: 18px; height: 18px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    display: none;
}
/* ── Panel ───────────────────────────────────────────── */
#dm-chat-panel {
    position: fixed; bottom: 88px; right: 24px; z-index: 9999;
    width: 340px; max-width: calc(100vw - 32px);
    background: #fff; border-radius: 16px;
    box-shadow: 0 8px 40px rgba(0,0,0,.18);
    display: flex; flex-direction: column; overflow: hidden;
    transform: translateY(20px) scale(.95); opacity: 0;
    pointer-events: none; transition: all .22s cubic-bezier(.34,1.56,.64,1);
}
#dm-chat-panel.open { transform: translateY(0) scale(1); opacity: 1; pointer-events: auto; }
/* header */
#dm-chat-header {
    background: linear-gradient(135deg,#0084ff,#00c6ff);
    padding: 14px 16px; display: flex; align-items: center; gap: 12px;
}
#dm-chat-header img { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,.4); }
#dm-chat-header .info { flex: 1; }
#dm-chat-header .name  { color: #fff; font-weight: 700; font-size: 14px; }
#dm-chat-header .status { color: rgba(255,255,255,.8); font-size: 11px; display: flex; align-items: center; gap: 4px; }
#dm-chat-header .dot { width: 6px; height: 6px; border-radius: 50%; background: #4ade80; }
#dm-chat-close { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.8); font-size: 18px; padding: 2px 4px; line-height: 1; }
#dm-chat-close:hover { color: #fff; }
/* messages */
#dm-chat-body {
    flex: 1; overflow-y: auto; padding: 12px; display: flex;
    flex-direction: column; gap: 8px; min-height: 240px; max-height: 300px;
    background: #f8fafc;
}
#dm-chat-body::-webkit-scrollbar { width: 4px; }
#dm-chat-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }
.dm-msg { max-width: 80%; display: flex; flex-direction: column; gap: 2px; }
.dm-msg.user  { align-self: flex-end; align-items: flex-end; }
.dm-msg.admin { align-self: flex-start; align-items: flex-start; }
.dm-msg .bubble { padding: 8px 12px; border-radius: 14px; font-size: 13.5px; line-height: 1.45; word-break: break-word; }
.dm-msg.user  .bubble { background: #0084ff; color: #fff; border-bottom-right-radius: 4px; }
.dm-msg.admin .bubble { background: #fff; color: #1e293b; border: 1px solid #e2e8f0; border-bottom-left-radius: 4px; }
.dm-msg .time { font-size: 10px; color: #94a3b8; }
#dm-chat-empty { text-align: center; margin: auto; color: #94a3b8; font-size: 13px; padding: 20px; }
#dm-chat-typing { font-size: 12px; color: #64748b; padding: 4px 8px; display: none; align-self: flex-start; }
/* ── Facebook gate (guests) ─────────────────────────── */
#dm-chat-fb-gate {
    padding: 18px 16px; display: flex; flex-direction: column;
    align-items: center; gap: 10px; border-top: 1px solid #f1f5f9; background: #fff;
}
#dm-chat-fb-gate p { font-size: 12.5px; color: #64748b; text-align: center; line-height: 1.5; margin: 0; }
#dm-chat-fb-err {
    font-size: 11.5px; color: #dc2626; background: #fef2f2; border: 1px solid #fecaca;
    border-radius: 8px; padding: 8px 12px; display: none; width: 100%; box-sizing: border-box; text-align: center;
}
#dm-chat-fb-login-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; background: #1877f2; color: #fff; font-size: 13px; font-weight: 700;
    padding: 10px 20px; border-radius: 999px; border: none; cursor: pointer;
    transition: background .15s; font-family: inherit;
}
#dm-chat-fb-login-btn:hover  { background: #1558b0; }
#dm-chat-fb-login-btn:disabled { background: #93c5fd; cursor: not-allowed; }
#dm-chat-fb-login-btn svg { width: 18px; height: 18px; fill: white; flex-shrink: 0; }
#dm-chat-fb-gate small { font-size: 11px; color: #94a3b8; text-align: center; }
/* ── Input ───────────────────────────────────────────── */
#dm-chat-footer {
    padding: 10px 12px; border-top: 1px solid #f1f5f9;
    display: flex; align-items: center; gap: 8px; background: #fff;
}
#dm-chat-input {
    flex: 1; border: 1px solid #e2e8f0; border-radius: 999px;
    padding: 8px 14px; font-size: 13px; outline: none; resize: none;
    font-family: inherit; line-height: 1.4; max-height: 80px; overflow-y: auto;
    transition: border-color .15s;
}
#dm-chat-input:focus { border-color: #0084ff; }
#dm-chat-send {
    width: 36px; height: 36px; border-radius: 50%; border: none;
    background: #0084ff; cursor: pointer; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s; padding: 0;
}
#dm-chat-send:hover { background: #006acc; }
#dm-chat-send svg { width: 16px; height: 16px; fill: #fff; }
#dm-chat-send:disabled { background: #cbd5e1; cursor: not-allowed; }
</style>

<button id="dm-chat-bubble" onclick="dmChatToggle()" aria-label="Chat with us">
    <svg viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.952 1.464 5.59 3.757 7.32V22l3.433-1.887c.917.254 1.889.392 2.894.392 5.523 0 10-4.145 10-9.262C22 6.145 17.523 2 12 2zm1.047 12.482L10.9 12.18l-4.316 2.303 4.742-5.03 2.176 2.302 4.284-2.302-4.739 5.029z"/></svg>
    <span>Chat with us</span>
    <span id="dm-chat-unread"></span>
</button>

<div id="dm-chat-panel" role="dialog" aria-label="Chat">
    <div id="dm-chat-header">
        <img src="https://graph.facebook.com/{{ $fbPageId }}/picture?type=square" alt="DealMindanao" onerror="this.src='/images/logo.png'">
        <div class="info">
            <div class="name">DealMindanao</div>
            <div class="status"><span class="dot"></span> Typically replies within an hour</div>
        </div>
        <button id="dm-chat-close" onclick="dmChatToggle()" aria-label="Close">&#x2715;</button>
    </div>

    <div id="dm-chat-body">
        <div id="dm-chat-empty">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <p style="margin-top:8px">Send us a message!<br>We'll reply as soon as we can.</p>
        </div>
        <div id="dm-chat-typing">Admin is typing&hellip;</div>
    </div>

    {{-- Facebook login gate — only shown to guests --}}
    <div id="dm-chat-fb-gate" style="display:none">
        <p>Please log in with Facebook<br>to send us a message.</p>
        <div id="dm-chat-fb-err"></div>
        <button id="dm-chat-fb-login-btn" onclick="dmFbLogin()">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.952 1.464 5.59 3.757 7.32V22l3.433-1.887c.917.254 1.889.392 2.894.392 5.523 0 10-4.145 10-9.262C22 6.145 17.523 2 12 2zm1.047 12.482L10.9 12.18l-4.316 2.303 4.742-5.03 2.176 2.302 4.284-2.302-4.739 5.029z"/></svg>
            Continue with Facebook
        </button>
        <small>Your Facebook identity helps us reply to you</small>
    </div>

    <div id="dm-chat-footer" style="display:none">
        <textarea id="dm-chat-input" rows="1" placeholder="Type a message&hellip;"></textarea>
        <button id="dm-chat-send" onclick="dmSendMessage()" aria-label="Send">
            <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </div>
</div>

<script>
(function () {
    var API   = (window.VITE_API_URL || '/api');
    var token = localStorage.getItem('auth_token');
    var isAuth = !!token;

    function getGuestToken() { return localStorage.getItem('dm_guest_token') || null; }
    function canChat()       { return isAuth || !!getGuestToken(); }

    function chatHeaders() {
        var h = { 'Accept': 'application/json' };
        if (isAuth) { h['Authorization'] = 'Bearer ' + token; }
        else { var gt = getGuestToken(); if (gt) h['X-Guest-Token'] = gt; }
        return h;
    }

    function showFooter() {
        var footer = document.getElementById('dm-chat-footer');
        var gate   = document.getElementById('dm-chat-fb-gate');
        if (canChat()) {
            if (footer) footer.style.display = '';
            if (gate)   gate.style.display   = 'none';
        } else {
            if (footer) footer.style.display = 'none';
            if (gate)   gate.style.display   = '';
        }
    }

    function showGateError(msg) {
        var el = document.getElementById('dm-chat-fb-err');
        if (!el) return;
        el.textContent = msg;
        el.style.display = 'block';
    }
    function hideGateError() {
        var el = document.getElementById('dm-chat-fb-err');
        if (el) el.style.display = 'none';
    }

    // ── Facebook OAuth popup ──────────────────────────────
    var fbPopup = null;

    window.dmFbLogin = function () {
        hideGateError();
        var btn = document.getElementById('dm-chat-fb-login-btn');

        // Close any existing popup
        if (fbPopup && !fbPopup.closed) { fbPopup.focus(); return; }

        var w = 600, h = 700;
        var left = Math.max(0, (screen.width  - w) / 2);
        var top  = Math.max(0, (screen.height - h) / 2);
        fbPopup = window.open(
            '/auth/fb-chat',
            'dm_fb_login',
            'width=' + w + ',height=' + h + ',left=' + left + ',top=' + top + ',resizable=yes,scrollbars=yes'
        );

        if (!fbPopup || fbPopup.closed) {
            showGateError('Popup was blocked. Please allow popups for this site and try again.');
            return;
        }

        if (btn) btn.textContent = 'Connecting…';
    };

    // Listen for postMessage from the OAuth callback page
    window.addEventListener('message', function (e) {
        if (e.origin !== window.location.origin) return;
        var btn = document.getElementById('dm-chat-fb-login-btn');

        if (e.data && e.data.type === 'dm_fb_auth') {
            localStorage.setItem('dm_guest_token', e.data.guestToken);
            showFooter();
            loadHistory();
            pollTimer = setInterval(poll, 3000);
            if (btn) btn.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" style="fill:white;flex-shrink:0"><path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.952 1.464 5.59 3.757 7.32V22l3.433-1.887c.917.254 1.889.392 2.894.392 5.523 0 10-4.145 10-9.262C22 6.145 17.523 2 12 2zm1.047 12.482L10.9 12.18l-4.316 2.303 4.742-5.03 2.176 2.302 4.284-2.302-4.739 5.029z"/></svg> Continue with Facebook';
            setTimeout(function() {
                var inp = document.getElementById('dm-chat-input');
                if (inp) inp.focus();
            }, 200);
        } else if (e.data && e.data.type === 'dm_fb_auth_error') {
            showGateError(e.data.error || 'Facebook login failed. Please try again.');
            if (btn) btn.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" style="fill:white;flex-shrink:0"><path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.952 1.464 5.59 3.757 7.32V22l3.433-1.887c.917.254 1.889.392 2.894.392 5.523 0 10-4.145 10-9.262C22 6.145 17.523 2 12 2zm1.047 12.482L10.9 12.18l-4.316 2.303 4.742-5.03 2.176 2.302 4.284-2.302-4.739 5.029z"/></svg> Continue with Facebook';
        }
    });

    var isOpen    = false;
    var lastId    = 0;
    var pollTimer = null;

    function formatTime(iso) {
        var d = new Date(iso);
        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
    }
    function appendMessage(msg) {
        var body  = document.getElementById('dm-chat-body');
        var empty = document.getElementById('dm-chat-empty');
        if (empty) empty.remove();
        var wrap = document.createElement('div');
        wrap.className  = 'dm-msg ' + msg.sender;
        wrap.dataset.id = msg.id;
        wrap.innerHTML  =
            '<div class="bubble">' + escHtml(msg.message) + '</div>' +
            '<div class="time">' + formatTime(msg.created_at) + '</div>';
        body.insertBefore(wrap, document.getElementById('dm-chat-typing'));
        body.scrollTop = body.scrollHeight;
        if (msg.id > lastId) lastId = msg.id;
    }
    function loadHistory() {
        fetch(API + '/chat/messages', { headers: chatHeaders() })
            .then(function(r){ return r.json(); })
            .then(function(msgs){ msgs.forEach(appendMessage); })
            .catch(function(){});
    }
    function poll() {
        if (!isOpen) return;
        fetch(API + '/chat/messages?after=' + lastId, { headers: chatHeaders() })
            .then(function(r){ return r.json(); })
            .then(function(msgs){
                msgs.forEach(function(m){
                    if (!document.querySelector('[data-id="' + m.id + '"]')) appendMessage(m);
                });
            }).catch(function(){});
    }
    window.dmSendMessage = function () {
        var input = document.getElementById('dm-chat-input');
        var btn   = document.getElementById('dm-chat-send');
        var text  = input.value.trim();
        if (!text) return;
        btn.disabled = true; input.value = ''; input.style.height = 'auto';
        var headers = chatHeaders();
        headers['Content-Type'] = 'application/json';
        fetch(API + '/chat/send', { method: 'POST', headers: headers, body: JSON.stringify({ message: text }) })
            .then(function(r){ return r.json(); })
            .then(function(msg){ appendMessage(msg); })
            .catch(function(){})
            .finally(function(){ btn.disabled = false; });
    };
    window.dmChatToggle = function () {
        var panel = document.getElementById('dm-chat-panel');
        isOpen = !isOpen;
        panel.classList.toggle('open', isOpen);
        if (isOpen) {
            showFooter();
            if (canChat()) {
                loadHistory();
                pollTimer = setInterval(poll, 3000);
                setTimeout(function(){
                    var inp = document.getElementById('dm-chat-input');
                    if (inp) inp.focus();
                }, 250);
            }
            var badge = document.getElementById('dm-chat-unread');
            if (badge) { badge.style.display = 'none'; badge.textContent = ''; }
        } else {
            clearInterval(pollTimer);
        }
    };
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('dm-chat-input');
        if (!input) return;
        input.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 80) + 'px';
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); dmSendMessage(); }
        });
    });
})();
</script>
