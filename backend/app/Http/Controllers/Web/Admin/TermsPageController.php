<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TermsSection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TermsPageController extends Controller
{
    private array $allKeys = [
        'tos_header_enabled',
        'tos_title',
        'tos_subtitle',
        'tos_footer_enabled',
        'tos_footer_text',
        'tos_footer_link_label',
        'tos_footer_link_url',
        'tos_last_updated',
        'tos_auto_update_date',
        'tos_meta_title',
        'tos_meta_description',
        'tos_meta_keywords',
        'tos_canonical',
    ];

    private array $defaults = [
        'tos_header_enabled'    => '1',
        'tos_title'             => 'Terms of Service',
        'tos_subtitle'          => 'Please read these terms carefully before using DealMindanao.',
        'tos_footer_enabled'    => '1',
        'tos_footer_text'       => 'For questions about these terms, please',
        'tos_footer_link_label' => 'contact our support team',
        'tos_footer_link_url'   => '/contact',
        'tos_last_updated'      => 'February 14, 2026',
        'tos_auto_update_date'  => '0',
        'tos_meta_title'        => 'Terms of Service - DealMindanao',
        'tos_meta_description'  => 'Read the DealMindanao terms of service before using our marketplace platform.',
        'tos_meta_keywords'     => 'terms of service, DealMindanao terms, conditions of use',
        'tos_canonical'         => '',
    ];

    private array $toggleKeys = [
        'tos_header_enabled',
        'tos_footer_enabled',
        'tos_auto_update_date',
    ];

    // ── Admin index ──────────────────────────────────────────────────────────

    public function index()
    {
        $raw = Setting::getMany($this->allKeys);
        $s   = [];
        foreach ($this->allKeys as $key) {
            $s[$key] = $raw[$key] ?? $this->defaults[$key];
        }

        $sections = TermsSection::ordered()->get();

        return view('admin.terms-page.index', compact('s', 'sections'));
    }

    // ── Save page-level settings ─────────────────────────────────────────────

    public function update(Request $request)
    {
        $request->validate([
            'tos_title'             => 'required|string|max:200',
            'tos_subtitle'          => 'nullable|string|max:500',
            'tos_footer_text'       => 'nullable|string|max:400',
            'tos_footer_link_label' => 'nullable|string|max:100',
            'tos_footer_link_url'   => 'nullable|string|max:200',
            'tos_last_updated'      => 'nullable|string|max:100',
            'tos_meta_title'        => 'nullable|string|max:70',
            'tos_meta_description'  => 'nullable|string|max:160',
            'tos_meta_keywords'     => 'nullable|string|max:300',
            'tos_canonical'         => 'nullable|string|max:300',
        ]);

        $autoUpdate = $request->has('tos_auto_update_date');

        foreach ($this->allKeys as $key) {
            if (in_array($key, $this->toggleKeys)) {
                Setting::set($key, $request->has($key) ? '1' : '0');
            } else {
                Setting::set($key, $request->input($key, ''));
            }
        }

        // Auto-stamp date if toggle was on before this save
        if ($autoUpdate) {
            Setting::set('tos_last_updated', Carbon::now()->format('F j, Y'));
        }

        return redirect()->route('admin.terms_page.index')
            ->with('success', 'Terms of Service settings saved.');
    }

    // ── Section: create ──────────────────────────────────────────────────────

    public function storeSection(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:300',
            'body'  => 'required|string',
        ]);

        $data['sort_order'] = TermsSection::max('sort_order') + 1;
        $data['is_active']  = true;

        TermsSection::create($data);

        $this->maybeAutoUpdateDate();

        return redirect()->route('admin.terms_page.index')
            ->with('success', 'Terms section added.');
    }

    // ── Section: update ──────────────────────────────────────────────────────

    public function updateSection(Request $request, TermsSection $section)
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

        $this->maybeAutoUpdateDate();

        return redirect()->route('admin.terms_page.index')
            ->with('success', 'Terms section updated.');
    }

    // ── Section: delete ──────────────────────────────────────────────────────

    public function destroySection(TermsSection $section)
    {
        $section->delete();

        $this->maybeAutoUpdateDate();

        return redirect()->route('admin.terms_page.index')
            ->with('success', 'Terms section deleted.');
    }

    // ── Section: toggle active ───────────────────────────────────────────────

    public function toggleSection(TermsSection $section)
    {
        $section->update(['is_active' => !$section->is_active]);

        return redirect()->route('admin.terms_page.index')
            ->with('success', 'Section visibility updated.');
    }

    // ── Section: save sort order (AJAX drag-and-drop) ────────────────────────

    public function reorderSections(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:terms_sections,id',
        ]);

        foreach ($request->order as $position => $id) {
            TermsSection::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function maybeAutoUpdateDate(): void
    {
        if (Setting::get('tos_auto_update_date', '0') === '1') {
            Setting::set('tos_last_updated', Carbon::now()->format('F j, Y'));
        }
    }
}
