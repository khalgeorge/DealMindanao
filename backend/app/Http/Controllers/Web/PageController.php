<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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
    
    public function contact()
    {
        return view('contact');
    }
    
    public function partner()
    {
        return view('partner');
    }
}
