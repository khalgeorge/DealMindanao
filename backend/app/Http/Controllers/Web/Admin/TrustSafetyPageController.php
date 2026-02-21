<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrustSafetyItem;
use App\Models\Setting;
use Illuminate\Http\Request;

class TrustSafetyPageController extends Controller
{
    // ── Settings keys ──────────────────────────────────────────────────────

    private array $allKeys = [
        'ts_header_enabled',
        'ts_title',
        'ts_subtitle',
        'ts_footer_enabled',
        'ts_footer_prefix',
        'ts_footer_contact_label',
        'ts_footer_contact_url',
        'ts_footer_suffix',
        'ts_footer_link1_label',
        'ts_footer_link1_url',
        'ts_footer_link2_label',
        'ts_footer_link2_url',
        'ts_footer_link3_label',
        'ts_footer_link3_url',
        'ts_footer_link4_label',
        'ts_footer_link4_url',
        'ts_meta_title',
        'ts_meta_description',
        'ts_meta_keywords',
        'ts_canonical',
    ];

    public array $defaults = [
        'ts_header_enabled'       => '1',
        'ts_title'                => 'Trust & Safety',
        'ts_subtitle'             => 'Your confidence and security are our top priorities at DealMindanao.',
        'ts_footer_enabled'       => '1',
        'ts_footer_prefix'        => 'For questions about our trust and safety measures, please',
        'ts_footer_contact_label' => 'contact our support team',
        'ts_footer_contact_url'   => '/contact',
        'ts_footer_suffix'        => 'or explore our policies:',
        'ts_footer_link1_label'   => 'Help Center →',
        'ts_footer_link1_url'     => '/help',
        'ts_footer_link2_label'   => 'Privacy Policy →',
        'ts_footer_link2_url'     => '/privacy',
        'ts_footer_link3_label'   => 'Refund Policy →',
        'ts_footer_link3_url'     => '/refunds',
        'ts_footer_link4_label'   => 'Terms of Service →',
        'ts_footer_link4_url'     => '/terms',
        'ts_meta_title'           => 'Trust & Safety - DealMindanao',
        'ts_meta_description'     => 'Learn about DealMindanao trust and safety measures. Your confidence and security are our top priorities.',
        'ts_meta_keywords'        => 'trust and safety, secure shopping, DealMindanao',
        'ts_canonical'            => '',
    ];

    private array $toggleKeys = ['ts_header_enabled', 'ts_footer_enabled'];

    // ── Admin index ─────────────────────────────────────────────────────────

    public function index()
    {
        $raw   = Setting::getMany($this->allKeys);
        $s     = [];
        foreach ($this->allKeys as $key) {
            $s[$key] = $raw[$key] ?? $this->defaults[$key];
        }

        $items = TrustSafetyItem::ordered()->get();

        return view('admin.trust-safety-page.index', compact('s', 'items'));
    }

    // ── Save page-level settings ────────────────────────────────────────────

    public function update(Request $request)
    {
        $request->validate([
            'ts_title'                => 'required|string|max:200',
            'ts_subtitle'             => 'required|string|max:500',
            'ts_footer_prefix'        => 'nullable|string|max:300',
            'ts_footer_contact_label' => 'nullable|string|max:100',
            'ts_footer_contact_url'   => 'nullable|string|max:200',
            'ts_footer_suffix'        => 'nullable|string|max:200',
            'ts_footer_link1_label'   => 'nullable|string|max:100',
            'ts_footer_link1_url'     => 'nullable|string|max:200',
            'ts_footer_link2_label'   => 'nullable|string|max:100',
            'ts_footer_link2_url'     => 'nullable|string|max:200',
            'ts_footer_link3_label'   => 'nullable|string|max:100',
            'ts_footer_link3_url'     => 'nullable|string|max:200',
            'ts_footer_link4_label'   => 'nullable|string|max:100',
            'ts_footer_link4_url'     => 'nullable|string|max:200',
            'ts_meta_title'           => 'nullable|string|max:70',
            'ts_meta_description'     => 'nullable|string|max:160',
            'ts_meta_keywords'        => 'nullable|string|max:300',
            'ts_canonical'            => 'nullable|string|max:300',
        ]);

        foreach ($this->allKeys as $key) {
            if (in_array($key, $this->toggleKeys)) {
                Setting::set($key, $request->has($key) ? '1' : '0');
            } else {
                Setting::set($key, $request->input($key, ''));
            }
        }

        return redirect()->route('admin.trust_safety.index')
            ->with('success', 'Trust & Safety page settings saved.');
    }

    // ── Item: create ────────────────────────────────────────────────────────

    public function storeItem(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:300',
            'description' => 'required|string',
            'icon_svg'    => 'nullable|string',
            'icon_color'  => 'required|in:brand,accent',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? (TrustSafetyItem::max('sort_order') + 1);
        $data['is_active']  = true;

        TrustSafetyItem::create($data);

        return redirect()->route('admin.trust_safety.index')
            ->with('success', 'Trust & Safety item added.');
    }

    // ── Item: update ────────────────────────────────────────────────────────

    public function updateItem(Request $request, TrustSafetyItem $item)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:300',
            'description' => 'required|string',
            'icon_svg'    => 'nullable|string',
            'icon_color'  => 'required|in:brand,accent',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $item->update([
            'title'       => $data['title'],
            'description' => $data['description'],
            'icon_svg'    => $data['icon_svg'] ?? $item->icon_svg,
            'icon_color'  => $data['icon_color'],
            'sort_order'  => $data['sort_order'] ?? $item->sort_order,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('admin.trust_safety.index')
            ->with('success', 'Trust & Safety item updated.');
    }

    // ── Item: delete ────────────────────────────────────────────────────────

    public function destroyItem(TrustSafetyItem $item)
    {
        $item->delete();

        return redirect()->route('admin.trust_safety.index')
            ->with('success', 'Trust & Safety item deleted.');
    }

    // ── Item: toggle active ─────────────────────────────────────────────────

    public function toggleItem(TrustSafetyItem $item)
    {
        $item->update(['is_active' => !$item->is_active]);

        return redirect()->route('admin.trust_safety.index')
            ->with('success', 'Item visibility updated.');
    }

    // ── Item: save sort order (POST JSON from drag-and-drop) ─────────────────

    public function reorderItems(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:trust_safety_items,id',
        ]);

        foreach ($request->order as $position => $id) {
            TrustSafetyItem::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
