<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use App\Models\PrivacySection;
use App\Models\RefundSection;
use App\Models\Setting;
use App\Models\TermsSection;
use App\Models\TrustSafetyItem;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private array $aboutDefaults = [
        'about_hero_title'              => 'About',
        'about_hero_title_highlight'    => 'DealMindanao',
        'about_hero_subtitle'           => 'DealMindanao is a curated online marketplace for hardware and heavy equipment in Mindanao. We connect customers with quality local deals while personally coordinating orders, payments, and delivery to ensure a smooth offline shopping experience.',
        'about_card1_title'             => 'Support Local',
        'about_card1_description'       => 'Every purchase directly supports small family-owned businesses in the Mindanao region.',
        'about_card2_title'             => 'Quality First',
        'about_card2_description'       => 'We hand-verify our producers to ensure you receive authentic, premium Mindanao goods.',
        'about_card3_title'             => 'Fast Delivery',
        'about_card3_description'       => "Smart logistics network ensures Mindanao's harvest reaches your doorstep rapidly.",
        'about_section_title'           => 'Authenticity in every',
        'about_section_title_highlight' => 'Package.',
        'about_story_paragraph1'        => 'DealMindanao was born out of a desire to centralize the fragmented marketplace of Mindanao. We saw talented weavers in Marawi, fruit farmers in Bukidnon, and seafood producers in Zamboanga struggling to find a wider audience.',
        'about_story_paragraph2'        => "By leveraging modern technology, we've built a platform that not only sells products but tells the stories of the people behind them.",
        'about_cta1_label'              => 'Shop Deals',
        'about_cta1_link'               => '/shop',
        'about_cta2_label'              => 'Become a Partner',
        'about_cta2_link'               => '/partner',
        'about_image'                   => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800',
    ];

    public function about()
    {
        $keys = array_keys($this->aboutDefaults);
        $raw  = Setting::getMany($keys);
        $s    = [];
        foreach ($keys as $key) {
            $s[$key] = $raw[$key] ?? $this->aboutDefaults[$key];
        }
        return view('about', ['s' => $s]);
    }
    
    private array $partnerDefaults = [
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
    ];

    private array $contactDefaults = [
        'contact_badge'           => 'Contact Support',
        'contact_title'           => "We're here to",
        'contact_title_highlight' => 'help',
        'contact_description'     => 'Need help with an order or have questions? Contact our support team and include your order number for faster assistance.',
        'contact_email'           => 'hello@dealmindanao.ph',
        'contact_address'         => 'Poblacion District, Davao City, 8000',
    ];

    public function contact()
    {
        $keys = array_keys($this->contactDefaults);
        $raw  = Setting::getMany($keys);
        $s    = [];
        foreach ($keys as $key) {
            $s[$key] = $raw[$key] ?? $this->contactDefaults[$key];
        }
        return view('contact', ['s' => $s]);
    }

    private function loadStaticPage(string $slug, string $defaultTitle, string $defaultSubtitle = ''): Page
    {
        return Page::firstOrCreate(
            ['slug' => $slug],
            ['title' => $defaultTitle, 'subtitle' => $defaultSubtitle]
        );
    }

    public function privacy()
    {
        $allKeys = [
            'pp_header_enabled',
            'pp_title',
            'pp_subtitle',
            'pp_footer_enabled',
            'pp_footer_text',
            'pp_footer_link_label',
            'pp_footer_link_url',
            'pp_last_updated',
            'pp_last_updated_auto',
        ];
        $defaults = [
            'pp_header_enabled'    => '1',
            'pp_title'             => 'Privacy Policy',
            'pp_subtitle'          => 'Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.',
            'pp_footer_enabled'    => '1',
            'pp_footer_text'       => 'For questions about your privacy or to exercise your rights, please',
            'pp_footer_link_label' => 'contact our support team',
            'pp_footer_link_url'   => '/contact',
            'pp_last_updated'      => 'February 14, 2026',
            'pp_last_updated_auto' => '0',
        ];
        $raw = Setting::getMany($allKeys);
        $s   = [];
        foreach ($allKeys as $key) {
            $s[$key] = $raw[$key] ?? $defaults[$key];
        }
        $sections = PrivacySection::active()->get();
        return view('privacy', compact('s', 'sections'));
    }

    public function terms()
    {
        $defaults = [
            'tos_header_enabled'    => '1',
            'tos_title'             => 'Terms of Service',
            'tos_subtitle'          => 'Please read these terms carefully before using DealMindanao.',
            'tos_footer_enabled'    => '1',
            'tos_footer_text'       => 'For questions about these terms, please',
            'tos_footer_link_label' => 'contact our support team',
            'tos_footer_link_url'   => '/contact',
            'tos_last_updated'      => 'February 14, 2026',
            'tos_auto_update_date'  => '0',
        ];
        $keys = array_keys($defaults);
        $raw  = Setting::whereIn('key', $keys)->pluck('value', 'key');
        $s    = array_merge($defaults, $raw->toArray());

        $sections = TermsSection::active()->get();

        return view('terms', compact('s', 'sections'));
    }

    public function help()
    {
        $defaults = [
            'help_header_enabled'  => '1',
            'help_title'           => 'Help Center',
            'help_subtitle'        => 'Find answers to frequently asked questions about ordering, payment, and delivery.',
            'help_cta_enabled'     => '1',
            'help_cta_title'       => 'Still have questions?',
            'help_cta_description' => 'Our support team is here to help you, or explore our policies for more details.',
            'help_cta_btn1_label'  => 'Contact Support',
            'help_cta_btn1_link'   => '/contact',
            'help_cta_btn2_label'  => 'Browse Deals',
            'help_cta_btn2_link'   => '/shop',
        ];

        $keys = array_keys($defaults);
        $raw  = Setting::getMany($keys);
        $s    = [];
        foreach ($keys as $key) {
            $s[$key] = $raw[$key] ?? $defaults[$key];
        }

        $faqs = Faq::active()->get();

        return view('help', compact('s', 'faqs'));
    }

    public function refunds()
    {
        $defaults = [
            'rp_header_enabled'    => '1',
            'rp_title'             => 'Refund & Returns Policy',
            'rp_subtitle'          => 'We want you to be satisfied with your purchase. Please review our return policy below.',
            'rp_footer_enabled'    => '1',
            'rp_footer_text'       => 'For questions about returns or to initiate a return, please',
            'rp_footer_link_label' => 'contact our support team',
            'rp_footer_link_url'   => '/contact',
        ];
        $keys = array_keys($defaults);
        $raw  = Setting::whereIn('key', $keys)->pluck('value', 'key');
        $s    = array_merge($defaults, $raw->toArray());

        $sections = RefundSection::active()->get();

        return view('refunds', compact('s', 'sections'));
    }

    public function trustSafety()
    {
        $allKeys = [
            'ts_header_enabled', 'ts_title', 'ts_subtitle',
            'ts_footer_enabled', 'ts_footer_prefix', 'ts_footer_contact_label',
            'ts_footer_contact_url', 'ts_footer_suffix',
            'ts_footer_link1_label', 'ts_footer_link1_url',
            'ts_footer_link2_label', 'ts_footer_link2_url',
            'ts_footer_link3_label', 'ts_footer_link3_url',
            'ts_footer_link4_label', 'ts_footer_link4_url',
        ];
        $defaults = [
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
        ];
        $raw = Setting::getMany($allKeys);
        $s   = [];
        foreach ($allKeys as $key) {
            $s[$key] = $raw[$key] ?? $defaults[$key];
        }
        $items = TrustSafetyItem::active()->get();
        return view('trust-safety', compact('s', 'items'));
    }

    public function partner()
    {
        $keys = array_keys($this->partnerDefaults);
        $raw  = Setting::getMany($keys);
        $s    = [];
        foreach ($keys as $key) {
            $s[$key] = $raw[$key] ?? $this->partnerDefaults[$key];
        }
        return view('partner', ['s' => $s]);
    }
}
