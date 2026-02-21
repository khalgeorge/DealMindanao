<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundSection;
use App\Models\Setting;
use Illuminate\Http\Request;

class RefundPolicyPageController extends Controller
{
    private array $allKeys = [
        'rp_header_enabled',
        'rp_title',
        'rp_subtitle',
        'rp_footer_enabled',
        'rp_footer_text',
        'rp_footer_link_label',
        'rp_footer_link_url',
        'rp_meta_title',
        'rp_meta_description',
        'rp_meta_keywords',
        'rp_canonical',
    ];

    private array $defaults = [
        'rp_header_enabled'    => '1',
        'rp_title'             => 'Refund & Returns Policy',
        'rp_subtitle'          => 'We want you to be satisfied with your purchase. Please review our return policy below.',
        'rp_footer_enabled'    => '1',
        'rp_footer_text'       => 'For questions about returns or to initiate a return, please',
        'rp_footer_link_label' => 'contact our support team',
        'rp_footer_link_url'    => '/contact',
        'rp_meta_title'         => 'Refund & Returns Policy - DealMindanao',
        'rp_meta_description'   => 'Learn about DealMindanao refund and returns policy. We want you to be completely satisfied with your purchase.',
        'rp_meta_keywords'      => 'refund policy, returns, DealMindanao',
        'rp_canonical'          => '',
    ];

    private array $toggleKeys = ['rp_header_enabled', 'rp_footer_enabled'];

    // ── Admin index ──────────────────────────────────────────────────────────

    public function index()
    {
        $raw = Setting::getMany($this->allKeys);
        $s   = [];
        foreach ($this->allKeys as $key) {
            $s[$key] = $raw[$key] ?? $this->defaults[$key];
        }

        $sections = RefundSection::ordered()->get();

        return view('admin.refund-policy-page.index', compact('s', 'sections'));
    }

    // ── Save page-level settings ─────────────────────────────────────────────

    public function update(Request $request)
    {
        $request->validate([
            'rp_title'             => 'required|string|max:200',
            'rp_subtitle'          => 'nullable|string|max:500',
            'rp_footer_text'       => 'nullable|string|max:400',
            'rp_footer_link_label' => 'nullable|string|max:100',
            'rp_footer_link_url'   => 'nullable|string|max:200',
            'rp_meta_title'        => 'nullable|string|max:70',
            'rp_meta_description'  => 'nullable|string|max:160',
            'rp_meta_keywords'     => 'nullable|string|max:300',
            'rp_canonical'         => 'nullable|string|max:300',
        ]);

        foreach ($this->allKeys as $key) {
            if (in_array($key, $this->toggleKeys)) {
                Setting::set($key, $request->has($key) ? '1' : '0');
            } else {
                Setting::set($key, $request->input($key, ''));
            }
        }

        return redirect()->route('admin.refund_policy.index')
            ->with('success', 'Refund & Returns Policy settings saved.');
    }

    // ── Section: create ──────────────────────────────────────────────────────

    public function storeSection(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:300',
            'body'  => 'required|string',
        ]);

        $data['sort_order'] = RefundSection::max('sort_order') + 1;
        $data['is_active']  = true;

        RefundSection::create($data);

        return redirect()->route('admin.refund_policy.index')
            ->with('success', 'Refund Policy section added.');
    }

    // ── Section: update ──────────────────────────────────────────────────────

    public function updateSection(Request $request, RefundSection $section)
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

        return redirect()->route('admin.refund_policy.index')
            ->with('success', 'Refund Policy section updated.');
    }

    // ── Section: delete ──────────────────────────────────────────────────────

    public function destroySection(RefundSection $section)
    {
        $section->delete();

        return redirect()->route('admin.refund_policy.index')
            ->with('success', 'Refund Policy section deleted.');
    }

    // ── Section: toggle active ───────────────────────────────────────────────

    public function toggleSection(RefundSection $section)
    {
        $section->update(['is_active' => !$section->is_active]);

        return redirect()->route('admin.refund_policy.index')
            ->with('success', 'Section visibility updated.');
    }

    // ── Section: save sort order (AJAX drag-and-drop) ────────────────────────

    public function reorderSections(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:refund_sections,id',
        ]);

        foreach ($request->order as $position => $id) {
            RefundSection::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
