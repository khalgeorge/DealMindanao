<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomePageController extends Controller
{
    private array $allKeys = [
        'home_meta_title', 'home_meta_description', 'home_meta_keywords', 'home_canonical',
        'home_hero_enabled', 'home_hero_slides',
        'home_highlights_enabled', 'home_highlights_badge', 'home_highlights_heading',
        'home_highlights_subtext', 'home_highlights_cta_text', 'home_highlights_cta_url',
        'home_benefits_enabled', 'home_benefits_heading', 'home_benefits_subtext', 'home_benefits_items',
        'home_steps_enabled', 'home_steps_heading', 'home_steps_subtext', 'home_steps_items',
        'home_steps_info_heading', 'home_steps_info_text',
        'home_steps_info_link1_text', 'home_steps_info_link1_url',
        'home_steps_info_link2_text', 'home_steps_info_link2_url',
        'home_cta_enabled', 'home_cta_heading', 'home_cta_subtext',
        'home_cta_btn1_text', 'home_cta_btn1_url',
        'home_cta_btn2_text', 'home_cta_btn2_url',
    ];

    public function index()
    {
        $s = Setting::getMany($this->allKeys);

        $s['home_hero_slides']    = json_decode($s['home_hero_slides'] ?? '[]', true) ?: $this->defaultSlides();
        $s['home_benefits_items'] = json_decode($s['home_benefits_items'] ?? '[]', true) ?: [];
        $s['home_steps_items']    = json_decode($s['home_steps_items'] ?? '[]', true) ?: [];

        return view('admin.home-page.index', ['s' => $s]);
    }

    // ── SEO / Meta ──────────────────────────────────────────────────────────
    public function updateMeta(Request $request)
    {
        $request->validate([
            'home_meta_title'       => 'required|string|max:70',
            'home_meta_description' => 'nullable|string|max:300',
            'home_meta_keywords'    => 'nullable|string|max:300',
            'home_canonical'        => 'nullable|string|max:300',
        ]);

        Setting::set('home_meta_title',       $request->input('home_meta_title', ''));
        Setting::set('home_meta_description', $request->input('home_meta_description', ''));
        Setting::set('home_meta_keywords',    $request->input('home_meta_keywords', ''));
        Setting::set('home_canonical',        $request->input('home_canonical', ''));

        return back()->with('success', 'SEO / Meta saved.')->with('tab', 'meta');
    }

    // ── Hero Carousel ────────────────────────────────────────────────────────
    public function updateHero(Request $request)
    {
        $request->validate([
            'slide_0_heading'           => 'required|string|max:255',
            'slide_0_heading_highlight' => 'nullable|string|max:255',
            'slide_0_highlight_class'   => 'nullable|string|max:50',
            'slide_0_subtext'           => 'nullable|string|max:500',
            'slide_0_cta_text'          => 'nullable|string|max:100',
            'slide_0_cta_url'           => 'nullable|string|max:255',
            'slide_0_pills'             => 'nullable|string|max:300',
            'slide_0_badge1'            => 'nullable|string|max:100',
            'slide_0_badge2'            => 'nullable|string|max:100',
            'slide_0_disclaimer'        => 'nullable|string|max:500',
            'slide_0_image_upload'      => 'nullable|image|max:5120',
            'slide_1_heading'           => 'required|string|max:255',
            'slide_1_heading_highlight' => 'nullable|string|max:255',
            'slide_1_highlight_class'   => 'nullable|string|max:50',
            'slide_1_subtext'           => 'nullable|string|max:500',
            'slide_1_cta_text'          => 'nullable|string|max:100',
            'slide_1_cta_url'           => 'nullable|string|max:255',
            'slide_1_pills'             => 'nullable|string|max:300',
            'slide_1_badge1'            => 'nullable|string|max:100',
            'slide_1_badge2'            => 'nullable|string|max:100',
            'slide_1_disclaimer'        => 'nullable|string|max:500',
            'slide_1_image_upload'      => 'nullable|image|max:5120',
        ]);

        $existing = json_decode(Setting::get('home_hero_slides', '[]'), true) ?: $this->defaultSlides();
        $slides = [];

        for ($i = 0; $i <= 1; $i++) {
            // Preserve existing image unless a new one is uploaded
            $image = $request->input("slide_{$i}_image_current", $existing[$i]['image'] ?? '');

            if ($request->hasFile("slide_{$i}_image_upload")) {
                // Delete old stored image (skip if it's a plain /path or http URL)
                $old = $existing[$i]['image'] ?? '';
                if ($old && !str_starts_with($old, '/') && !str_starts_with($old, 'http')) {
                    Storage::disk('public')->delete($old);
                }
                $image = $request->file("slide_{$i}_image_upload")->store('home/hero', 'public');
            }

            $slides[] = [
                'image'             => $image,
                'heading'           => $request->input("slide_{$i}_heading", ''),
                'heading_highlight' => $request->input("slide_{$i}_heading_highlight", ''),
                'highlight_class'   => $request->input("slide_{$i}_highlight_class", 'text-brand-400'),
                'subtext'           => $request->input("slide_{$i}_subtext", ''),
                'cta_text'          => $request->input("slide_{$i}_cta_text", ''),
                'cta_url'           => $request->input("slide_{$i}_cta_url", ''),
                'pills'             => $request->input("slide_{$i}_pills", ''),
                'badge1'            => $request->input("slide_{$i}_badge1", ''),
                'badge2'            => $request->input("slide_{$i}_badge2", ''),
                'disclaimer'        => $request->input("slide_{$i}_disclaimer", ''),
            ];
        }

        Setting::set('home_hero_enabled', $request->has('home_hero_enabled') ? '1' : '0');
        Setting::set('home_hero_slides',  json_encode($slides));

        return back()->with('success', 'Hero section saved.')->with('tab', 'hero');
    }

    // ── Weekly Highlights ────────────────────────────────────────────────────
    public function updateHighlights(Request $request)
    {
        $request->validate([
            'home_highlights_badge'    => 'nullable|string|max:100',
            'home_highlights_heading'  => 'required|string|max:255',
            'home_highlights_subtext'  => 'nullable|string|max:500',
            'home_highlights_cta_text' => 'nullable|string|max:100',
            'home_highlights_cta_url'  => 'nullable|string|max:255',
        ]);

        Setting::set('home_highlights_enabled',  $request->has('home_highlights_enabled') ? '1' : '0');
        Setting::set('home_highlights_badge',    $request->input('home_highlights_badge', ''));
        Setting::set('home_highlights_heading',  $request->input('home_highlights_heading', ''));
        Setting::set('home_highlights_subtext',  $request->input('home_highlights_subtext', ''));
        Setting::set('home_highlights_cta_text', $request->input('home_highlights_cta_text', ''));
        Setting::set('home_highlights_cta_url',  $request->input('home_highlights_cta_url', ''));

        return back()->with('success', 'Weekly Highlights section saved.')->with('tab', 'highlights');
    }

    // ── Why Shop (Benefits) ──────────────────────────────────────────────────
    public function updateBenefits(Request $request)
    {
        $request->validate([
            'home_benefits_heading'  => 'required|string|max:255',
            'home_benefits_subtext'  => 'nullable|string|max:500',
            'benefit_title.*'        => 'required|string|max:100',
            'benefit_description.*'  => 'required|string|max:300',
        ]);

        $titles = $request->input('benefit_title', []);
        $descs  = $request->input('benefit_description', []);
        $items  = [];
        for ($i = 0, $len = count($titles); $i < $len; $i++) {
            $items[] = ['title' => $titles[$i], 'description' => $descs[$i]];
        }

        Setting::set('home_benefits_enabled', $request->has('home_benefits_enabled') ? '1' : '0');
        Setting::set('home_benefits_heading', $request->input('home_benefits_heading', ''));
        Setting::set('home_benefits_subtext', $request->input('home_benefits_subtext', ''));
        Setting::set('home_benefits_items',   json_encode($items));

        return back()->with('success', 'Why Shop section saved.')->with('tab', 'benefits');
    }

    // ── What to Expect (Steps) ───────────────────────────────────────────────
    public function updateSteps(Request $request)
    {
        $request->validate([
            'home_steps_heading'          => 'required|string|max:255',
            'home_steps_subtext'          => 'nullable|string|max:500',
            'step_title.*'                => 'required|string|max:100',
            'step_description.*'          => 'required|string|max:300',
            'step_time_label.*'           => 'nullable|string|max:100',
            'home_steps_info_heading'     => 'nullable|string|max:255',
            'home_steps_info_text'        => 'nullable|string|max:500',
            'home_steps_info_link1_text'  => 'nullable|string|max:100',
            'home_steps_info_link1_url'   => 'nullable|string|max:255',
            'home_steps_info_link2_text'  => 'nullable|string|max:100',
            'home_steps_info_link2_url'   => 'nullable|string|max:255',
        ]);

        $titles = $request->input('step_title', []);
        $descs  = $request->input('step_description', []);
        $times  = $request->input('step_time_label', []);
        $items  = [];
        for ($i = 0, $len = count($titles); $i < $len; $i++) {
            $items[] = [
                'title'       => $titles[$i],
                'description' => $descs[$i],
                'time_label'  => $times[$i] ?? '',
            ];
        }

        Setting::set('home_steps_enabled',         $request->has('home_steps_enabled') ? '1' : '0');
        Setting::set('home_steps_heading',         $request->input('home_steps_heading', ''));
        Setting::set('home_steps_subtext',         $request->input('home_steps_subtext', ''));
        Setting::set('home_steps_items',           json_encode($items));
        Setting::set('home_steps_info_heading',    $request->input('home_steps_info_heading', ''));
        Setting::set('home_steps_info_text',       $request->input('home_steps_info_text', ''));
        Setting::set('home_steps_info_link1_text', $request->input('home_steps_info_link1_text', ''));
        Setting::set('home_steps_info_link1_url',  $request->input('home_steps_info_link1_url', ''));
        Setting::set('home_steps_info_link2_text', $request->input('home_steps_info_link2_text', ''));
        Setting::set('home_steps_info_link2_url',  $request->input('home_steps_info_link2_url', ''));

        return back()->with('success', 'How It Works section saved.')->with('tab', 'steps');
    }

    // ── Call to Action ───────────────────────────────────────────────────────
    public function updateCta(Request $request)
    {
        $request->validate([
            'home_cta_heading'   => 'required|string|max:255',
            'home_cta_subtext'   => 'nullable|string|max:500',
            'home_cta_btn1_text' => 'nullable|string|max:100',
            'home_cta_btn1_url'  => 'nullable|string|max:255',
            'home_cta_btn2_text' => 'nullable|string|max:100',
            'home_cta_btn2_url'  => 'nullable|string|max:255',
        ]);

        Setting::set('home_cta_enabled',   $request->has('home_cta_enabled') ? '1' : '0');
        Setting::set('home_cta_heading',   $request->input('home_cta_heading', ''));
        Setting::set('home_cta_subtext',   $request->input('home_cta_subtext', ''));
        Setting::set('home_cta_btn1_text', $request->input('home_cta_btn1_text', ''));
        Setting::set('home_cta_btn1_url',  $request->input('home_cta_btn1_url', ''));
        Setting::set('home_cta_btn2_text', $request->input('home_cta_btn2_text', ''));
        Setting::set('home_cta_btn2_url',  $request->input('home_cta_btn2_url', ''));

        return back()->with('success', 'Call to Action section saved.')->with('tab', 'cta');
    }

    private function defaultSlides(): array
    {
        return [
            [
                'image' => '/hero1.png', 'heading' => "Discover Authentic\nMindanao Hardware & Heavy Equipment",
                'heading_highlight' => 'Hardware & Heavy Equipment', 'highlight_class' => 'text-brand-400',
                'subtext' => 'Quality tools and equipment from trusted local sellers — order online, pay offline, delivered with care.',
                'cta_text' => 'Shop Deals', 'cta_url' => '/shop',
                'pills' => 'Power Tools, Home Improvement, Outdoor Gear',
                'badge1' => 'Trusted Sellers', 'badge2' => 'Cash on Delivery',
                'disclaimer' => 'Order request only. No online payment required. Our team will contact you to confirm.',
            ],
            [
                'image' => '/hero2.png', 'heading' => "Quality Hardware\nFrom Local Sellers",
                'heading_highlight' => 'Hardware', 'highlight_class' => 'text-accent-400',
                'subtext' => 'Browse curated deals and support Mindanao entrepreneurs with every purchase.',
                'cta_text' => 'Shop Deals', 'cta_url' => '/shop',
                'pills' => 'Power Tools, Home Improvement, Outdoor Gear',
                'badge1' => 'Trusted Sellers', 'badge2' => 'Cash on Delivery',
                'disclaimer' => 'Order request only. No online payment required. Our team will contact you to confirm.',
            ],
        ];
    }
}
