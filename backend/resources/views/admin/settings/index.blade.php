@extends('layouts.admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Settings</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Platform Parameters</p>
    </div>
    <button id="save-all-btn" class="btn-primary btn-sm">Save Changes</button>
</header>

<div class="admin-content">
    @if(session('success'))
        <div class="alert-success mb-6">{{ session('success') }}</div>
    @endif

    <div class="space-y-6">

            <!-- General Tab -->
            <div id="tab-general" class="settings-tab">
                <form id="form-general" action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($errors->any())
                    <div class="alert-error mb-4">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="bg-white rounded-lg border border-gray-100 p-8 shadow-sm">
                    <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">Store Profile</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Platform Name</label>
                            <input type="text" name="platform_name" value="{{ $settings['platform_name'] ?? 'DealMindanao Marketplace' }}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 font-bold focus:ring-2 focus:ring-brand-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Support Email</label>
                            <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'support@dealmindanao.ph' }}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 font-bold focus:ring-2 focus:ring-brand-500 outline-none">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Header Logo</label>
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="w-12 h-12 bg-white rounded-lg border border-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                            <img id="header-logo-preview" src="{{ $settings['header_logo'] ? Storage::url($settings['header_logo']) : '/logo-no-bg.png' }}" class="max-h-8 w-auto" onerror="this.style.display='none'">
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $settings['header_logo'] ? basename($settings['header_logo']) : 'No logo uploaded' }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase">Light Background Version</p>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input type="file" id="header-logo-upload" name="header_logo" class="hidden" accept="image/*" onchange="updateFileName(this, 'header-logo-name')">
                                        <label for="header-logo-upload" class="flex items-center justify-center gap-2 w-full py-2 px-4 bg-white border border-gray-200 rounded-lg text-xs font-black text-gray-600 hover:bg-gray-100 cursor-pointer transition-colors uppercase tracking-widest">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            Upload New
                                        </label>
                                        <p id="header-logo-name" class="text-[10px] text-brand-600 font-bold mt-2 text-center"></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Footer Logo</label>
                                <div class="p-4 bg-gray-900 border border-gray-800 rounded-lg">
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="w-12 h-12 bg-gray-800 rounded-lg border border-gray-700 flex items-center justify-center overflow-hidden shrink-0">
                                            <img id="footer-logo-preview" src="{{ $settings['footer_logo'] ? Storage::url($settings['footer_logo']) : '/logo-no-bg.png' }}" class="max-h-8 w-auto brightness-200" onerror="this.style.display='none'">
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-white">{{ $settings['footer_logo'] ? basename($settings['footer_logo']) : 'No logo uploaded' }}</p>
                                            <p class="text-[10px] text-gray-500 font-bold uppercase">Dark Background Version</p>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input type="file" id="footer-logo-upload" name="footer_logo" class="hidden" accept="image/*" onchange="updateFileName(this, 'footer-logo-name')">
                                        <label for="footer-logo-upload" class="flex items-center justify-center gap-2 w-full py-2 px-4 bg-gray-800 border border-gray-700 rounded-lg text-xs font-black text-gray-300 hover:bg-gray-700 cursor-pointer transition-colors uppercase tracking-widest">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            Upload New
                                        </label>
                                        <p id="footer-logo-name" class="text-[10px] text-brand-400 font-bold mt-2 text-center"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Favicon</label>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="w-12 h-12 bg-white rounded-lg border border-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                        <img id="favicon-preview"
                                            src="/favicon.png?v={{ file_exists(public_path('favicon.png')) ? filemtime(public_path('favicon.png')) : '0' }}"
                                            class="w-8 h-8 object-contain"
                                            onerror="this.style.display='none'">
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">favicon.ico</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">16&times;16 or 32&times;32 &bull; .ico or .png</p>
                                    </div>
                                </div>
                                <div class="relative">
                                    <input type="file" id="favicon-upload" name="favicon" class="hidden" accept=".ico,image/x-icon,image/png,image/gif" onchange="updateFileName(this, 'favicon-name')">
                                    <label for="favicon-upload" class="flex items-center justify-center gap-2 w-full py-2 px-4 bg-white border border-gray-200 rounded-lg text-xs font-black text-gray-600 hover:bg-gray-100 cursor-pointer transition-colors uppercase tracking-widest">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Upload New
                                    </label>
                                    <p id="favicon-name" class="text-[10px] text-brand-600 font-bold mt-2 text-center"></p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Maintenance Mode</label>
                            <div class="flex items-center gap-3">
                                <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out bg-gray-200 rounded-full cursor-pointer">
                                    <input type="checkbox" name="maintenance_mode" value="1" {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' }} class="absolute w-6 h-6 transition duration-200 ease-in-out transform bg-white border-2 border-gray-200 rounded-full appearance-none cursor-pointer checked:translate-x-6 checked:bg-brand-600 checked:border-brand-600"/>
                                </div>
                                <span class="text-sm font-bold text-gray-500 italic">{{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'On (Maintenance)' : 'Off (Live)' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>

            <!-- Regional Tab -->
            <div id="tab-regional" class="settings-tab hidden">
                <form id="form-regional" action="{{ route('admin.settings.regional.update') }}" method="POST">
                @csrf
                <div class="bg-white rounded-lg border border-gray-100 p-8 shadow-sm">
                    <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">Mindanao Logistics</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Default Currency</label>
                            <select name="currency" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 font-bold outline-none">
                                <option value="PHP" {{ ($settings['currency'] ?? 'PHP') === 'PHP' ? 'selected' : '' }}>Philippine Peso (PHP)</option>
                                <option value="USD" {{ ($settings['currency'] ?? 'PHP') === 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Supported Regions</label>
                            <div class="grid grid-cols-2 gap-4">
                                @php $activeRegions = $settings['regions'] ?? []; @endphp
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" name="regions[]" value="davao" {{ in_array('davao', $activeRegions) ? 'checked' : '' }} class="accent-brand-600 w-5 h-5">
                                    <span class="text-sm font-bold">Davao Region</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" name="regions[]" value="northern_mindanao" {{ in_array('northern_mindanao', $activeRegions) ? 'checked' : '' }} class="accent-brand-600 w-5 h-5">
                                    <span class="text-sm font-bold">Northern Mindanao</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" name="regions[]" value="zamboanga" {{ in_array('zamboanga', $activeRegions) ? 'checked' : '' }} class="accent-brand-600 w-5 h-5">
                                    <span class="text-sm font-bold">Zamboanga Peninsula</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" name="regions[]" value="soccsksargen" {{ in_array('soccsksargen', $activeRegions) ? 'checked' : '' }} class="accent-brand-600 w-5 h-5">
                                    <span class="text-sm font-bold">SOCCSKSARGEN</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>

            <!-- Security Tab -->
            <div id="tab-security" class="settings-tab hidden">
                <div class="bg-white rounded-lg border border-gray-100 p-8 shadow-sm">
                    <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">Access Control</h2>
                    <div class="space-y-4">
                        <button onclick="openPasswordModal()" class="w-full text-left p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-100 transition-all flex justify-between items-center group">
                            <div>
                                <p class="font-bold text-gray-900">Change Admin Password</p>
                                <p class="text-xs text-gray-500">Last changed 2 months ago</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <button onclick="open2FAModal()" class="w-full text-left p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-100 transition-all flex justify-between items-center group">
                            <div>
                                <p class="font-bold text-gray-900">Two-Factor Authentication</p>
                                <p class="text-xs text-green-600 font-bold uppercase tracking-widest">Enabled</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SMS & Email Tab -->
            <div id="tab-notifications" class="settings-tab hidden">
                <form id="form-notifications" action="{{ route('admin.settings.notifications.update') }}" method="POST">
                @csrf
                <div class="bg-white rounded-lg border border-gray-100 p-8 shadow-sm">
                    <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">Alert Configuration</h2>
                    <div class="space-y-8">
                        <!-- Email -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="font-black text-gray-900 uppercase text-sm tracking-widest">Email Notifications (SMTP)</h3>
                                <span class="text-[10px] font-bold py-1 px-2 bg-green-50 text-green-700 rounded-lg">CONNECTED</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Host</label>
                                    <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] ?? config('mail.mailers.smtp.host', 'smtp.postmarkapp.com') }}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 font-bold text-sm outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Port</label>
                                    <input type="text" name="smtp_port" value="{{ $settings['smtp_port'] ?? config('mail.mailers.smtp.port', '587') }}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 font-bold text-sm outline-none">
                                </div>
                            </div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="order_pdf_copy" value="1" {{ ($settings['order_pdf_copy'] ?? '1') === '1' ? 'checked' : '' }} class="accent-brand-600 w-4 h-4">
                                <span class="text-sm font-bold text-gray-600">Send copy of Order PDF to Customer</span>
                            </label>
                        </div>

                        <!-- SMS -->
                        <div class="space-y-4 pt-8 border-t border-gray-50">
                            <div class="flex items-center justify-between">
                                <h3 class="font-black text-gray-900 uppercase text-sm tracking-widest">SMS Gateway (Sema)</h3>
                                <span class="text-[10px] font-bold py-1 px-2 bg-brand-50 text-brand-700 rounded-lg">1,240 CREDITS REMAINING</span>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">API Key</label>
                                <input type="password" name="sms_api_key" placeholder="{{ ($settings['sms_api_key'] ?? '') ? '••••••••••••••••' : 'Enter SMS API key' }}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 font-bold text-sm outline-none">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="notify_admin_order" value="1" {{ ($settings['notify_admin_order'] ?? '1') === '1' ? 'checked' : '' }} class="accent-brand-600 w-4 h-4">
                                    <span class="text-sm font-bold text-gray-600">Notify Admin on New Order</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="notify_customer_order" value="1" {{ ($settings['notify_customer_order'] ?? '1') === '1' ? 'checked' : '' }} class="accent-brand-600 w-4 h-4">
                                    <span class="text-sm font-bold text-gray-600">Notify Courier on Pickup Ready</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>

    </div>
</div>

<!-- Change Password Modal -->
<div id="password-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closePasswordModal()"></div>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <button onclick="closePasswordModal()" class="absolute top-4 right-4 z-10 p-2 text-gray-400 hover:text-gray-600 bg-white rounded-full shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="p-8">
                <div class="mb-6">
                    <div class="w-12 h-12 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Change Password</h3>
                    <p class="text-sm text-gray-500 text-center">Update your admin account password</p>
                </div>
                <form id="password-form" action="{{ route('admin.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Current Password</label>
                        <input type="password" name="current_password" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 font-medium focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Enter current password">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">New Password</label>
                        <input type="password" name="password" required minlength="8" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 font-medium focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Enter new password">
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 font-medium focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Re-enter new password">
                    </div>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex gap-3">
                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-xs text-amber-800">You will be logged out and need to sign in again with your new password.</p>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closePasswordModal()" class="btn-outline flex-1">Cancel</button>
                        <button type="submit" class="btn-primary flex-1">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Two-Factor Authentication Modal -->
<div id="twofa-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="close2FAModal()"></div>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <button onclick="close2FAModal()" class="absolute top-4 right-4 z-10 p-2 text-gray-400 hover:text-gray-600 bg-white rounded-full shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="p-8">
                <div class="mb-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Two-Factor Authentication</h3>
                    <p class="text-sm text-gray-500 text-center">Add an extra layer of security to your account</p>
                </div>
                <div class="space-y-6">
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-green-900">Status</span>
                            <span class="px-3 py-1 bg-green-600 text-white text-xs font-bold rounded-full uppercase tracking-widest">Enabled</span>
                        </div>
                        <p class="text-xs text-green-700">Your account is protected with 2FA via SMS</p>
                    </div>
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Authentication Method</h4>
                        <label class="flex items-center justify-between p-4 bg-gray-50 border-2 border-green-500 rounded-lg cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">SMS Verification</p>
                                    <p class="text-xs text-gray-500">+63 9** *** **45</p>
                                </div>
                            </div>
                            <input type="radio" name="2fa-method" value="sms" checked class="accent-green-600 w-5 h-5">
                        </label>
                        <label class="flex items-center justify-between p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-gray-300">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Authenticator App</p>
                                    <p class="text-xs text-gray-500">Google Authenticator, Authy</p>
                                </div>
                            </div>
                            <input type="radio" name="2fa-method" value="app" class="accent-brand-600 w-5 h-5">
                        </label>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button onclick="disable2FA()" class="btn-outline flex-1 text-red-600 border-red-200 hover:bg-red-50">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            Disable 2FA
                        </button>
                        <button onclick="save2FA()" class="btn-primary flex-1">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(tabId) {
    document.querySelectorAll('.settings-tab').forEach(t => t.classList.add('hidden'));
    document.getElementById('tab-' + tabId).classList.remove('hidden');
}

function updateFileName(input, targetId) {
    if (input.files && input.files[0]) {
        document.getElementById(targetId).textContent = 'Selected: ' + input.files[0].name;
        // Live preview for image files
        const previewId = targetId.replace('-name', '-preview');
        const preview = document.getElementById(previewId);
        if (preview) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
}

// Restore active tab — URL query param takes priority, then flash session
window.addEventListener('DOMContentLoaded', function () {
    var urlTab     = new URLSearchParams(window.location.search).get('tab');
    @if(session('tab'))
    var sessionTab = '{{ session('tab') }}';
    @else
    var sessionTab = null;
    @endif
    var tab = urlTab || sessionTab;
    if (tab) switchTab(tab);
});

document.getElementById('save-all-btn').addEventListener('click', () => {
    const activeForm = document.querySelector('.settings-tab:not(.hidden) form');
    if (activeForm) {
        activeForm.submit();
    }
});

// Password Modal
function openPasswordModal() {
    document.getElementById('password-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePasswordModal() {
    document.getElementById('password-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('password-form').reset();
}

// 2FA Modal
function open2FAModal() {
    document.getElementById('twofa-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function close2FAModal() {
    document.getElementById('twofa-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function save2FA() {
    const method = document.querySelector('input[name="2fa-method"]:checked').value;
    alert('2FA method updated to ' + (method === 'sms' ? 'SMS' : 'Authenticator App') + '!');
    close2FAModal();
}

function disable2FA() {
    if (confirm('Are you sure you want to disable Two-Factor Authentication? This will make your account less secure.')) {
        alert('2FA has been disabled');
        close2FAModal();
    }
}
</script>
@endpush
