<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivacySection;
use App\Models\Setting;
use Illuminate\Http\Request;

class PrivacyPageController extends Controller
{
    // ── Settings keys ────────────────────────────────────────────────────────

    private array $allKeys = [
        'pp_header_enabled',
        'pp_title',
        'pp_subtitle',
        'pp_footer_enabled',
        'pp_footer_text',
        'pp_footer_link_label',
        'pp_footer_link_url',
        'pp_last_updated',
        'pp_last_updated_auto',
        'pp_meta_title',
        'pp_meta_description',
        'pp_meta_keywords',
        'pp_canonical',
    ];

    private array $defaults = [
        'pp_header_enabled'    => '1',
        'pp_title'             => 'Privacy Policy',
        'pp_subtitle'          => 'Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.',
        'pp_footer_enabled'    => '1',
        'pp_footer_text'       => 'For questions about your privacy or to exercise your rights, please',
        'pp_footer_link_label' => 'contact our support team',
        'pp_footer_link_url'   => '/contact',
        'pp_last_updated'      => 'February 14, 2026',
        'pp_last_updated_auto' => '0',
        'pp_meta_title'        => 'Privacy Policy - DealMindanao',
        'pp_meta_description'  => 'Learn how DealMindanao collects, uses, and protects your personal information.',
        'pp_meta_keywords'     => 'privacy policy, data protection, DealMindanao',
        'pp_canonical'         => '',
    ];

    private array $toggleKeys = [
        'pp_header_enabled',
        'pp_footer_enabled',
        'pp_last_updated_auto',
    ];

    // ── Admin index ──────────────────────────────────────────────────────────

    public function index()
    {
        $raw = Setting::getMany($this->allKeys);
        $s   = [];
        foreach ($this->allKeys as $key) {
            $s[$key] = $raw[$key] ?? $this->defaults[$key];
        }

        $sections = PrivacySection::ordered()->get();

        return view('admin.privacy-page.index', compact('s', 'sections'));
    }

    // ── Save page-level settings ─────────────────────────────────────────────

    public function update(Request $request)
    {
        $request->validate([
            'pp_title'             => 'required|string|max:200',
            'pp_subtitle'          => 'nullable|string|max:500',
            'pp_footer_text'       => 'nullable|string|max:400',
            'pp_footer_link_label' => 'nullable|string|max:100',
            'pp_footer_link_url'   => 'nullable|string|max:200',
            'pp_last_updated'      => 'nullable|string|max:50',
            'pp_meta_title'        => 'nullable|string|max:70',
            'pp_meta_description'  => 'nullable|string|max:160',
            'pp_meta_keywords'     => 'nullable|string|max:300',
            'pp_canonical'         => 'nullable|string|max:300',
        ]);

        foreach ($this->allKeys as $key) {
            if (in_array($key, $this->toggleKeys)) {
                Setting::set($key, $request->has($key) ? '1' : '0');
            } else {
                Setting::set($key, $request->input($key, ''));
            }
        }

        // Auto-stamp today if the option is on
        if ($request->has('pp_last_updated_auto')) {
            Setting::set('pp_last_updated', now()->format('F j, Y'));
        }

        return redirect()->route('admin.privacy_page.index')
            ->with('success', 'Privacy Policy settings saved.');
    }

    // ── Section: create ──────────────────────────────────────────────────────

    public function storeSection(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:300',
            'body'  => 'required|string',
        ]);

        $data['sort_order'] = PrivacySection::max('sort_order') + 1;
        $data['is_active']  = true;

        PrivacySection::create($data);

        $this->maybeAutoDate();

        return redirect()->route('admin.privacy_page.index')
            ->with('success', 'Privacy Policy section added.');
    }

    // ── Section: update ──────────────────────────────────────────────────────

    public function updateSection(Request $request, PrivacySection $section)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:300',
            'body'       => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $section->update([
            'title'      => $data['title'],
            'body'       => $data['body'],
            'sort_order' => $data['sort_order'] ?? $section->sort_order,
            'is_active'  => $request->has('is_active'),
        ]);

        $this->maybeAutoDate();

        return redirect()->route('admin.privacy_page.index')
            ->with('success', 'Privacy Policy section updated.');
    }

    // ── Section: delete ──────────────────────────────────────────────────────

    public function destroySection(PrivacySection $section)
    {
        $section->delete();

        $this->maybeAutoDate();

        return redirect()->route('admin.privacy_page.index')
            ->with('success', 'Privacy Policy section deleted.');
    }

    // ── Section: toggle active ───────────────────────────────────────────────

    public function toggleSection(PrivacySection $section)
    {
        $section->update(['is_active' => !$section->is_active]);

        return redirect()->route('admin.privacy_page.index')
            ->with('success', 'Section visibility updated.');
    }

    // ── Section: save sort order (POST JSON from drag-and-drop) ─────────────

    public function reorderSections(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:privacy_sections,id',
        ]);

        foreach ($request->order as $position => $id) {
            PrivacySection::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /** If auto-date is enabled, stamp today onto pp_last_updated. */
    private function maybeAutoDate(): void
    {
        $auto = Setting::get('pp_last_updated_auto', '0');
        if ($auto === '1') {
            Setting::set('pp_last_updated', now()->format('F j, Y'));
        }
    }
}
