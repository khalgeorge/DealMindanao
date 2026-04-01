{{--
    Messenger Chat Bubble — public pages only
    ─────────────────────────────────────────
    • Uses m.me link (fb-customerchat SDK was deprecated by Meta).
    • Only rendered when APP_ENV=production AND FACEBOOK_PAGE_ID is set.
--}}
@php
    $fbPageId = config('services.facebook.page_id', '');
@endphp

@if(app()->environment('production') && $fbPageId)
<style>
    #messenger-bubble {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        height: 52px;
        border-radius: 999px;
        background: linear-gradient(135deg, #0084ff, #00c6ff);
        box-shadow: 0 4px 16px rgba(0,132,255,0.45);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 18px 0 14px;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    #messenger-bubble:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 24px rgba(0,132,255,0.55);
    }
    #messenger-bubble svg {
        width: 26px;
        height: 26px;
        fill: #ffffff;
        flex-shrink: 0;
    }
    #messenger-bubble span {
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        letter-spacing: 0.01em;
    }
</style>
<a id="messenger-bubble"
   href="https://m.me/{{ $fbPageId }}"
   aria-label="Chat with us on Messenger"
   onclick="event.preventDefault();
            window.open(this.href,'MessengerChat',
              'width=420,height=640,top='+(screen.height/2-320)+',left='+(screen.width/2-210)+
              ',resizable=yes,scrollbars=yes,status=no,toolbar=no,menubar=no,location=no');">
    {{-- Official Messenger logo SVG --}}
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.952 1.464 5.59 3.757 7.32V22l3.433-1.887c.917.254 1.889.392 2.894.392 5.523 0 10-4.145 10-9.262C22 6.145 17.523 2 12 2zm1.047 12.482L10.9 12.18l-4.316 2.303 4.742-5.03 2.176 2.302 4.284-2.302-4.739 5.029z"/>
    </svg>
    <span>Chat with us</span>
</a>
@endif

