<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Setting;
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
        $page = $this->loadStaticPage('privacy', 'Privacy Policy', 'Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.');
        return view('static-page', compact('page'));
    }

    public function terms()
    {
        $page = $this->loadStaticPage('terms', 'Terms of Service', 'Please read these terms carefully before using DealMindanao.');
        return view('static-page', compact('page'));
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
        $page = $this->loadStaticPage('refunds', 'Refund & Returns Policy', 'We want you to be satisfied with your purchase. Please review our return policy below.');
        return view('static-page', compact('page'));
    }

    public function trustSafety()
    {
        $page = $this->loadStaticPage('trust-safety', 'Trust & Safety', 'Your confidence and security are our top priorities at DealMindanao.');
        return view('static-page', compact('page'));
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
