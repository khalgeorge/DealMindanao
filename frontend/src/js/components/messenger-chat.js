/**
 * Facebook Messenger Customer Chat Plugin
 *
 * - Only loads when VITE_FB_PAGE_ID is set and the hostname is NOT a
 *   local/dev address (localhost, 127.x, 192.168.x, 10.x, 172.16-31.x)
 * - Lazy-loads the SDK after the page 'load' event — fully non-blocking
 * - DOM elements are injected at runtime so they never block HTML parsing
 * - Called by layout.js only for public (non-admin) pages
 *
 * Required env var (frontend/.env):
 *   VITE_FB_PAGE_ID=<your numeric Facebook Page ID>
 *
 * How to find your numeric Page ID:
 *   facebook.com/dealmindanao → About → Page transparency → Page ID
 */

const DEV_HOST_RE = /^(localhost|127\.\d+\.\d+\.\d+)$|^192\.168\.|^10\.|^172\.(1[6-9]|2\d|3[01])\./;

function isProductionHost() {
  return !DEV_HOST_RE.test(window.location.hostname);
}

export function initMessengerChat() {
  const pageId = import.meta.env.VITE_FB_PAGE_ID;

  // Guard: skip on dev/localhost or when the env var is not configured
  if (!isProductionHost() || !pageId) return;

  window.addEventListener('load', () => {
    // Required anchor element for the Facebook SDK
    const fbRoot = document.createElement('div');
    fbRoot.id = 'fb-root';
    document.body.appendChild(fbRoot);

    // Customer Chat plugin element
    // The FB SDK scans for this and renders the chat bubble
    const chat = document.createElement('div');
    chat.className = 'fb-customerchat';
    chat.setAttribute('attribution', 'biz_inbox');
    chat.setAttribute('page_id', pageId);
    chat.setAttribute('logged_in_greeting',  'Hi! Need help with your order or delivery in Mindanao?');
    chat.setAttribute('logged_out_greeting', 'Hi! Need help with your order or delivery in Mindanao?');
    // Accessibility: the iframe injected by the SDK carries its own title,
    // but label the host element too for screen-reader discovery
    chat.setAttribute('role', 'complementary');
    chat.setAttribute('aria-label', 'Chat with us on Messenger');
    document.body.appendChild(chat);

    // Async SDK bootstrap — set fbAsyncInit BEFORE the script tag is inserted
    window.fbAsyncInit = function () {
      FB.init({ xfbml: true, version: 'v19.0' });
    };

    // Create and inject the SDK <script> tag asynchronously
    if (!document.getElementById('facebook-jssdk')) {
      const js = document.createElement('script');
      js.id = 'facebook-jssdk';
      js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
      js.async = true;
      js.defer = true;
      js.crossOrigin = 'anonymous';
      const anchor = document.getElementsByTagName('script')[0];
      anchor.parentNode.insertBefore(js, anchor);
    }
  }, { once: true });
}
