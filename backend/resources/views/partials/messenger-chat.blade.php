{{-- Custom Chat Widget — visible on all public pages --}}
@php
    $chatUser = auth()->user();
    $isLoggedIn = !!$chatUser;
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
#dm-chat-bubble span { color: #fff; font-size: 13px; font-weight: 700; white-space: nowrap; letter-spacing: .01em; }
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
    flex-direction: column; gap: 8px; min-height: 280px; max-height: 340px;
    background: #f8fafc;
}
#dm-chat-body::-webkit-scrollbar { width: 4px; }
#dm-chat-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }

.dm-msg { max-width: 80%; display: flex; flex-direction: column; gap: 2px; }
.dm-msg.user { align-self: flex-end; align-items: flex-end; }
.dm-msg.admin { align-self: flex-start; align-items: flex-start; }
.dm-msg .bubble {
    padding: 8px 12px; border-radius: 14px; font-size: 13.5px;
    line-height: 1.45; word-break: break-word;
}
.dm-msg.user  .bubble { background: #0084ff; color: #fff; border-bottom-right-radius: 4px; }
.dm-msg.admin .bubble { background: #fff; color: #1e293b; border: 1px solid #e2e8f0; border-bottom-left-radius: 4px; }
.dm-msg .time { font-size: 10px; color: #94a3b8; }

/* empty / typing states */
#dm-chat-empty { text-align: center; margin: auto; color: #94a3b8; font-size: 13px; padding: 20px; }
#dm-chat-typing { font-size: 12px; color: #64748b; padding: 4px 8px; display: none; align-self: flex-start; }

/* Facebook Messenger button (guests) */
#dm-chat-fb-footer {
    padding: 14px 16px; display: flex; flex-direction: column; align-items: center; gap: 10px;
    border-top: 1px solid #f1f5f9; background: #fff;
}
#dm-chat-fb-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; background: #1877f2; color: #fff; font-size: 13px; font-weight: 700;
    padding: 10px 22px; border-radius: 999px; text-decoration: none; transition: background .15s;
}
#dm-chat-fb-btn:hover { background: #1558b0; }
#dm-chat-fb-footer small { font-size: 11px; color: #94a3b8; text-align: center; }

/* footer / input */
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

{{-- Bubble button --}}
<button id="dm-chat-bubble" onclick="dmChatToggle()" aria-label="Chat with us">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.952 1.464 5.59 3.757 7.32V22l3.433-1.887c.917.254 1.889.392 2.894.392 5.523 0 10-4.145 10-9.262C22 6.145 17.523 2 12 2zm1.047 12.482L10.9 12.18l-4.316 2.303 4.742-5.03 2.176 2.302 4.284-2.302-4.739 5.029z"/>
    </svg>
    <span>Chat with us</span>
    <span id="dm-chat-unread"></span>
</button>

{{-- Chat panel --}}
<div id="dm-chat-panel" role="dialog" aria-label="Chat">
    {{-- Header --}}
    <div id="dm-chat-header">
        <img src="https://graph.facebook.com/{{ config('services.facebook.page_id') }}/picture?type=square" alt="DealMindanao" onerror="this.src='/images/logo.png'">
        <div class="info">
            <div class="name">DealMindanao</div>
            <div class="status"><span class="dot"></span> Typically replies within an hour</div>
        </div>
        <button id="dm-chat-close" onclick="dmChatToggle()" aria-label="Close chat">✕</button>
    </div>

    {{-- Messages --}}
    <div id="dm-chat-body">
        <div id="dm-chat-empty">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <p style="margin-top:8px" id="dm-chat-empty-text">Send us a message!<br>We'll reply as soon as we can.</p>
        </div>
        <div id="dm-chat-typing">Admin is typing…</div>
    </div>
    {{-- Input: auth users get textarea, guests get FB button — switched by JS --}}
    <div id="dm-chat-footer" style="display:none">
        <textarea id="dm-chat-input" rows="1" placeholder="Type a message…"></textarea>
        <button id="dm-chat-send" onclick="dmSendMessage()" aria-label="Send">
            <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </div>
    <div id="dm-chat-fb-footer" style="display:none">
        <a href="https://m.me/{{ config('services.facebook.page_id') }}"
           target="_blank" rel="noopener noreferrer" id="dm-chat-fb-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                <path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.952 1.464 5.59 3.757 7.32V22l3.433-1.887c.917.254 1.889.392 2.894.392 5.523 0 10-4.145 10-9.262C22 6.145 17.523 2 12 2zm1.047 12.482L10.9 12.18l-4.316 2.303 4.742-5.03 2.176 2.302 4.284-2.302-4.739 5.029z"/>
            </svg>
            Message us on Facebook
        </a>
        <small>A Facebook account is required to chat with us</small>
    </div>
</div>

<script>
(function () {
    const API      = (window.VITE_API_URL || '/api');
    const token    = localStorage.getItem('auth_token');
    const isAuth   = !!localStorage.getItem('auth_token');

    // Request headers for authenticated users
    function chatHeaders() {
        return { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token };
    }

    // Show the correct footer based on auth state
    (function initFooter() {
        if (isAuth) {
            document.getElementById('dm-chat-footer').style.display = '';
            const t = document.getElementById('dm-chat-empty-text');
            if (t) t.innerHTML = "Send us a message!<br>We'll reply as soon as we can.";
        } else {
            document.getElementById('dm-chat-fb-footer').style.display = '';
            const t = document.getElementById('dm-chat-empty-text');
            if (t) t.innerHTML = "Hi! How can we help?<br>Click below to message us<br>via Facebook Messenger.";
        }
    })();

    let isOpen     = false;
    let lastId     = 0;
    let pollTimer  = null;

    // ── helpers ──────────────────────────────────────────
    function formatTime(iso) {
        const d = new Date(iso);
        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function appendMessage(msg) {
        const body = document.getElementById('dm-chat-body');
        const empty = document.getElementById('dm-chat-empty');
        if (empty) empty.remove();

        const wrap = document.createElement('div');
        wrap.className = 'dm-msg ' + msg.sender;
        wrap.dataset.id = msg.id;
        wrap.innerHTML =
            '<div class="bubble">' + escHtml(msg.message) + '</div>' +
            '<div class="time">' + formatTime(msg.created_at) + '</div>';
        // insert before typing indicator
        const typing = document.getElementById('dm-chat-typing');
        body.insertBefore(wrap, typing);
        body.scrollTop = body.scrollHeight;

        if (msg.id > lastId) lastId = msg.id;
    }

    function escHtml(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
    }

    // ── load history ─────────────────────────────────────
    function loadHistory() {
        fetch(API + '/chat/messages', {
            headers: chatHeaders()
        })
        .then(r => r.json())
        .then(msgs => {
            msgs.forEach(appendMessage);
        })
        .catch(() => {});
    }

    // ── poll for new messages ─────────────────────────────
    function poll() {
        if (!isOpen) return;
        fetch(API + '/chat/messages?after=' + lastId, {
            headers: chatHeaders()
        })
        .then(r => r.json())
        .then(msgs => {
            msgs.forEach(m => {
                if (!document.querySelector('[data-id="' + m.id + '"]')) {
                    appendMessage(m);
                }
            });
        })
        .catch(() => {});
    }

    // ── send message ──────────────────────────────────────
    window.dmSendMessage = function () {
        const input = document.getElementById('dm-chat-input');
        const btn   = document.getElementById('dm-chat-send');
        const text  = input.value.trim();
        if (!text) return;

        btn.disabled = true;
        input.value  = '';
        input.style.height = 'auto';

        const headers = chatHeaders();
        headers['Content-Type'] = 'application/json';
        fetch(API + '/chat/send', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ message: text })
        })
        .then(r => r.json())
        .then(msg => appendMessage(msg))
        .catch(() => {})
        .finally(() => { btn.disabled = false; });
    };

    // ── toggle panel ──────────────────────────────────────
    window.dmChatToggle = function () {
        const panel = document.getElementById('dm-chat-panel');
        isOpen = !isOpen;
        panel.classList.toggle('open', isOpen);

        if (isOpen) {
            if (isAuth) {
                loadHistory();
                pollTimer = setInterval(poll, 3000);
                setTimeout(() => {
                    const input = document.getElementById('dm-chat-input');
                    if (input) input.focus();
                }, 250);
            }
            // clear unread badge
            const badge = document.getElementById('dm-chat-unread');
            if (badge) { badge.style.display = 'none'; badge.textContent = ''; }
        } else {
            clearInterval(pollTimer);
        }
    };

    // ── auto-resize textarea ──────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('dm-chat-input');
        if (!input) return;
        input.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 80) + 'px';
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                dmSendMessage();
            }
        });
    });
})();
</script>
