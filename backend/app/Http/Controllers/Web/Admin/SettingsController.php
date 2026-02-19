<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    protected array $allowedPages = [
        'home'         => 'Home',
        'about'        => 'About',
        'contact'      => 'Contact',
        'privacy'      => 'Privacy Policy',
        'terms'        => 'Terms & Conditions',
        'partner'      => 'Partner With Us',
        'help'         => 'Help / FAQ',
        'refunds'      => 'Refunds & Returns',
        'trust-safety' => 'Trust & Safety',
    ];

    public function index()
    {
        $settings = Setting::getMany([
            'platform_name',
            'support_email',
            'header_logo',
            'footer_logo',
            'maintenance_mode',
            'currency',
            'regions',
            'smtp_host',
            'smtp_port',
            'order_pdf_copy',
            'sms_api_key',
            'notify_admin_order',
            'notify_customer_order',
        ]);

        $settings['regions'] = json_decode($settings['regions'] ?? '[]', true) ?? [];

        return view('admin.settings.index', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'platform_name'    => 'required|string|max:255',
            'support_email'    => 'required|email|max:255',
            'header_logo'      => 'nullable|image|max:2048',
            'footer_logo'      => 'nullable|image|max:2048',
            'favicon'          => 'nullable|file|max:5120',
            'maintenance_mode' => 'nullable',
        ]);

        Setting::set('platform_name',    $request->platform_name);
        Setting::set('support_email',    $request->support_email);
        Setting::set('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0');

        if ($request->hasFile('header_logo')) {
            $old = Setting::get('header_logo');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('header_logo', $request->file('header_logo')->store('settings/logos', 'public'));
        }

        if ($request->hasFile('footer_logo')) {
            $old = Setting::get('footer_logo');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('footer_logo', $request->file('footer_logo')->store('settings/logos', 'public'));
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            // Write to backend public as both .ico and .png
            $file->move(public_path(), 'favicon.ico');
            copy(public_path('favicon.ico'), public_path('favicon.png'));
            // Mirror to frontend public (same host volume)
            $frontendPublic = base_path('../frontend/public');
            if (is_dir($frontendPublic)) {
                copy(public_path('favicon.ico'), $frontendPublic . '/favicon.png');
            }
        }

        return back()->with('success', 'General settings saved.')->with('tab', 'general');
    }

    public function updateRegional(Request $request)
    {
        $request->validate([
            'currency'  => 'required|string|max:10',
            'regions'   => 'nullable|array',
            'regions.*' => 'string|max:50',
        ]);

        Setting::set('currency', $request->currency);
        Setting::set('regions',  json_encode($request->input('regions', [])));

        return back()->with('success', 'Regional settings saved.')->with('tab', 'regional');
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'smtp_host'   => 'nullable|string|max:255',
            'smtp_port'   => 'nullable|integer',
            'sms_api_key' => 'nullable|string|max:255',
        ]);

        Setting::set('smtp_host',             $request->smtp_host ?? '');
        Setting::set('smtp_port',             $request->smtp_port ?? '587');
        Setting::set('order_pdf_copy',        $request->has('order_pdf_copy') ? '1' : '0');
        Setting::set('notify_admin_order',    $request->has('notify_admin_order') ? '1' : '0');
        Setting::set('notify_customer_order', $request->has('notify_customer_order') ? '1' : '0');

        $smsKey = $request->sms_api_key;
        if ($smsKey && $smsKey !== '****************') {
            Setting::set('sms_api_key', $smsKey);
        }

        return back()->with('success', 'Notification settings saved.')->with('tab', 'notifications');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->with('tab', 'security');
        }

        User::find($user->id)->update(['password' => Hash::make($request->password)]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Password updated. Please log in with your new password.');
    }

    public function pagesIndex()
    {
        $pages = Page::whereIn('slug', array_keys($this->allowedPages))
            ->get()
            ->keyBy('slug');

        return view('admin.settings.pages.index', [
            'pages'   => $pages,
            'allowed' => $this->allowedPages,
        ]);
    }

    public function pagesEdit(string $slug)
    {
        abort_unless(array_key_exists($slug, $this->allowedPages), 404);

        $page = Page::firstOrCreate(
            ['slug' => $slug],
            ['title' => $this->allowedPages[$slug]]
        );

        return view('admin.settings.pages.edit', [
            'page'  => $page,
            'label' => $this->allowedPages[$slug],
        ]);
    }

    public function pagesUpdate(Request $request, string $slug)
    {
        abort_unless(array_key_exists($slug, $this->allowedPages), 404);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'subtitle'         => 'nullable|string|max:255',
            'body'             => 'nullable|string',
            'logo'             => 'nullable|image|max:2048',
            'hero_image'       => 'nullable|image|max:4096',
        ]);

        $page = Page::firstOrCreate(
            ['slug' => $slug],
            ['title' => $this->allowedPages[$slug]]
        );

        $data = collect($validated)->except(['logo', 'hero_image'])->toArray();

        if ($request->hasFile('logo')) {
            if ($page->logo_path) Storage::disk('public')->delete($page->logo_path);
            $data['logo_path'] = $request->file('logo')->store('pages/logos', 'public');
        }

        if ($request->hasFile('hero_image')) {
            if ($page->hero_image_path) Storage::disk('public')->delete($page->hero_image_path);
            $data['hero_image_path'] = $request->file('hero_image')->store('pages/heroes', 'public');
        }

        $page->update($data);

        return redirect()->route('admin.settings.pages.index')
            ->with('success', $this->allowedPages[$slug] . ' page updated successfully!');
    }
}
