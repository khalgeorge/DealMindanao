<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Http\Request;

class HelpPageController extends Controller
{
    // ── Settings keys for the Help page (non-FAQ content) ──────────────────

    private array $allKeys = [
        'help_header_enabled',
        'help_title',
        'help_subtitle',
        'help_cta_enabled',
        'help_cta_title',
        'help_cta_description',
        'help_cta_btn1_label',
        'help_cta_btn1_link',
        'help_cta_btn2_label',
        'help_cta_btn2_link',
        'help_meta_title',
        'help_meta_description',
        'help_meta_keywords',
        'help_canonical',
    ];

    public array $defaults = [
        'help_header_enabled'  => '1',
        'help_title'           => 'Help Center',
        'help_subtitle'        => 'Find answers to frequently asked questions about ordering, payment, and delivery.',
        'help_cta_enabled'     => '1',
        'help_cta_title'       => 'Still have questions?',
        'help_cta_description' => 'Our support team is here to help you, or explore our policies for more details.',
        'help_cta_btn1_label'  => 'Contact Support',
        'help_cta_btn1_link'   => '/contact',
        'help_cta_btn2_label'  => 'Browse Deals',
        'help_cta_btn2_link'    => '/shop',
        'help_meta_title'       => 'Help Center - DealMindanao',
        'help_meta_description' => 'Find answers to frequently asked questions about ordering, payment, and delivery on DealMindanao.',
        'help_meta_keywords'    => 'DealMindanao help, FAQ, ordering, payment, delivery',
        'help_canonical'        => '',
    ];

    private array $toggleKeys = ['help_header_enabled', 'help_cta_enabled'];

    // ── Admin index ─────────────────────────────────────────────────────────

    public function index()
    {
        $raw  = Setting::getMany($this->allKeys);
        $s    = [];
        foreach ($this->allKeys as $key) {
            $s[$key] = $raw[$key] ?? $this->defaults[$key];
        }

        $faqs = Faq::ordered()->get();

        return view('admin.help-page.index', compact('s', 'faqs'));
    }

    // ── Save page-level settings ────────────────────────────────────────────

    public function update(Request $request)
    {
        $request->validate([
            'help_title'           => 'required|string|max:200',
            'help_subtitle'        => 'required|string|max:500',
            'help_cta_title'       => 'required|string|max:200',
            'help_cta_description' => 'required|string|max:500',
            'help_cta_btn1_label'  => 'required|string|max:100',
            'help_cta_btn1_link'   => 'required|string|max:200',
            'help_cta_btn2_label'  => 'required|string|max:100',
            'help_cta_btn2_link'    => 'required|string|max:200',
            'help_meta_title'       => 'nullable|string|max:70',
            'help_meta_description' => 'nullable|string|max:160',
            'help_meta_keywords'    => 'nullable|string|max:300',
            'help_canonical'        => 'nullable|string|max:300',
        ]);

        foreach ($this->allKeys as $key) {
            if (in_array($key, $this->toggleKeys)) {
                Setting::set($key, $request->has($key) ? '1' : '0');
            } else {
                Setting::set($key, $request->input($key, ''));
            }
        }

        return redirect()->route('admin.help_page.index')
            ->with('success', 'Help page settings saved.');
    }

    // ── FAQ: create ─────────────────────────────────────────────────────────

    public function storeFaq(Request $request)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:300',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? (Faq::max('sort_order') + 1);
        $data['is_active']  = true;

        Faq::create($data);

        return redirect()->route('admin.help_page.index')
            ->with('success', 'FAQ added successfully.');
    }

    // ── FAQ: update ─────────────────────────────────────────────────────────

    public function updateFaq(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:300',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable',
        ]);

        $faq->update([
            'question'   => $data['question'],
            'answer'     => $data['answer'],
            'sort_order' => $data['sort_order'] ?? $faq->sort_order,
            'is_active'  => $request->has('is_active'),
        ]);

        return redirect()->route('admin.help_page.index')
            ->with('success', 'FAQ updated.');
    }

    // ── FAQ: delete ─────────────────────────────────────────────────────────

    public function destroyFaq(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.help_page.index')
            ->with('success', 'FAQ deleted.');
    }

    // ── FAQ: toggle active via POST (for inline toggle) ─────────────────────

    public function toggleFaq(Faq $faq)
    {
        $faq->update(['is_active' => !$faq->is_active]);

        return redirect()->route('admin.help_page.index')
            ->with('success', 'FAQ visibility updated.');
    }

    // ── FAQ: save sort order (POST JSON from drag-and-drop) ─────────────────

    public function reorderFaqs(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:faqs,id',
        ]);

        foreach ($request->order as $position => $id) {
            Faq::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
