<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PartnerPageController extends Controller
{
    /** All 27 settings keys for the Partner page. */
    private array $allKeys = [
        // Hero
        'partner_hero_enabled',
        'partner_hero_badge',
        'partner_hero_title',
        'partner_hero_title_highlight',
        'partner_hero_description',
        'partner_hero_cta_label',
        'partner_hero_cta_link',
        // Features section
        'partner_features_enabled',
        'partner_card1_enabled', 'partner_card1_number', 'partner_card1_title', 'partner_card1_description',
        'partner_card2_enabled', 'partner_card2_number', 'partner_card2_title', 'partner_card2_description',
        'partner_card3_enabled', 'partner_card3_number', 'partner_card3_title', 'partner_card3_description',
        // CTA / Testimonial section
        'partner_cta_enabled',
        'partner_cta_title',
        'partner_cta_quote',
        'partner_cta_btn1_label', 'partner_cta_btn1_link',
        'partner_cta_btn2_label', 'partner_cta_btn2_link',
        'partner_meta_title',
        'partner_meta_description',
        'partner_meta_keywords',
        'partner_canonical',
    ];

    /** Default values matching the current frontend template. */
    private array $defaults = [
        'partner_hero_enabled'          => '1',
        'partner_hero_badge'            => 'Partner Program',
        'partner_hero_title'            => 'Become a',
        'partner_hero_title_highlight'  => 'Partner',
        'partner_hero_description'      => 'Partner with DealMindanao to showcase your products to more customers online. We handle listings, pricing strategy, customer communication, and order coordination — so you can focus on supplying quality products.',
        'partner_hero_cta_label'        => 'Become a Partner',
        'partner_hero_cta_link'         => '#apply',
        'partner_features_enabled'      => '1',
        'partner_card1_enabled'         => '1',
        'partner_card1_number'          => '01',
        'partner_card1_title'           => 'Fast Onboarding',
        'partner_card1_description'     => 'Get your shop up and running in less than 48 hours with our easy setup process.',
        'partner_card2_enabled'         => '1',
        'partner_card2_number'          => '02',
        'partner_card2_title'           => 'Unified Logistics',
        'partner_card2_description'     => "We handle the pickup and shipping. Just pack your products and we'll do the rest.",
        'partner_card3_enabled'         => '1',
        'partner_card3_number'          => '03',
        'partner_card3_title'           => 'Growth Insights',
        'partner_card3_description'     => 'Access detailed analytics and market trends to help you optimize your inventory.',
        'partner_cta_enabled'           => '1',
        'partner_cta_title'             => 'Ready to Scale?',
        'partner_cta_quote'             => '"Our sales doubled within three months of joining DealMindanao. The logistics support is a game-changer for Mindanao farms."',
        'partner_cta_btn1_label'        => 'Contact Us to Partner',
        'partner_cta_btn1_link'         => '/contact',
        'partner_cta_btn2_label'        => 'Learn About Us',
        'partner_cta_btn2_link'         => '/about',
        'partner_meta_title'            => 'Become a Partner - DealMindanao',
        'partner_meta_description'      => 'Partner with DealMindanao to showcase your products to more customers online. We handle listings, pricing, and order coordination.',
        'partner_meta_keywords'         => 'DealMindanao partner, sell online, Mindanao business partner',
        'partner_canonical'             => '',
    ];

    /** Keys that are boolean toggles (checkboxes). */
    private array $toggleKeys = [
        'partner_hero_enabled',
        'partner_features_enabled',
        'partner_card1_enabled',
        'partner_card2_enabled',
        'partner_card3_enabled',
        'partner_cta_enabled',
    ];

    public function index()
    {
        $raw = Setting::getMany($this->allKeys);
        $s   = [];
        foreach ($this->allKeys as $key) {
            $s[$key] = $raw[$key] ?? $this->defaults[$key] ?? '';
        }

        return view('admin.partner-page.index', ['s' => $s]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'partner_hero_badge'            => 'nullable|string|max:100',
            'partner_hero_title'            => 'required|string|max:255',
            'partner_hero_title_highlight'  => 'required|string|max:255',
            'partner_hero_description'      => 'required|string|max:1000',
            'partner_hero_cta_label'        => 'required|string|max:100',
            'partner_hero_cta_link'         => 'required|string|max:255',
            'partner_card1_number'          => 'required|string|max:10',
            'partner_card1_title'           => 'required|string|max:100',
            'partner_card1_description'     => 'required|string|max:500',
            'partner_card2_number'          => 'required|string|max:10',
            'partner_card2_title'           => 'required|string|max:100',
            'partner_card2_description'     => 'required|string|max:500',
            'partner_card3_number'          => 'required|string|max:10',
            'partner_card3_title'           => 'required|string|max:100',
            'partner_card3_description'     => 'required|string|max:500',
            'partner_cta_title'             => 'required|string|max:255',
            'partner_cta_quote'             => 'required|string|max:1000',
            'partner_cta_btn1_label'        => 'required|string|max:100',
            'partner_cta_btn1_link'         => 'required|string|max:255',
            'partner_cta_btn2_label'   => 'required|string|max:100',
            'partner_cta_btn2_link'    => 'required|string|max:255',
            'partner_meta_title'       => 'nullable|string|max:70',
            'partner_meta_description' => 'nullable|string|max:160',
            'partner_meta_keywords'    => 'nullable|string|max:300',
            'partner_canonical'        => 'nullable|string|max:300',
        ]);

        // Toggles: unchecked checkboxes are absent from the request
        foreach ($this->toggleKeys as $key) {
            Setting::set($key, $request->has($key) ? '1' : '0');
        }

        // All other text fields
        $textKeys = array_diff($this->allKeys, $this->toggleKeys);
        foreach ($textKeys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return back()->with('success', 'Partner page content saved successfully.');
    }
}
