<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutPageController extends Controller
{
    private array $allKeys = [
        'about_hero_title',
        'about_hero_title_highlight',
        'about_hero_subtitle',
        'about_card1_title', 'about_card1_description',
        'about_card2_title', 'about_card2_description',
        'about_card3_title', 'about_card3_description',
        'about_section_title', 'about_section_title_highlight',
        'about_story_paragraph1', 'about_story_paragraph2',
        'about_cta1_label', 'about_cta1_link',
        'about_cta2_label', 'about_cta2_link',
        'about_image',
    ];

    private array $defaults = [
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

    public function index()
    {
        $raw = Setting::getMany($this->allKeys);
        $s   = [];
        foreach ($this->allKeys as $key) {
            $s[$key] = $raw[$key] ?? $this->defaults[$key] ?? '';
        }

        return view('admin.about-page.index', ['s' => $s]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'about_hero_title'              => 'required|string|max:255',
            'about_hero_title_highlight'    => 'required|string|max:255',
            'about_hero_subtitle'           => 'required|string|max:1000',
            'about_card1_title'             => 'required|string|max:100',
            'about_card1_description'       => 'required|string|max:500',
            'about_card2_title'             => 'required|string|max:100',
            'about_card2_description'       => 'required|string|max:500',
            'about_card3_title'             => 'required|string|max:100',
            'about_card3_description'       => 'required|string|max:500',
            'about_section_title'           => 'required|string|max:255',
            'about_section_title_highlight' => 'required|string|max:100',
            'about_story_paragraph1'        => 'required|string|max:1000',
            'about_story_paragraph2'        => 'required|string|max:1000',
            'about_cta1_label'              => 'required|string|max:100',
            'about_cta1_link'               => 'required|string|max:255',
            'about_cta2_label'              => 'required|string|max:100',
            'about_cta2_link'               => 'required|string|max:255',
            'about_image_upload'            => 'nullable|image|max:5120',
            'about_image_url'               => 'nullable|string|max:500',
        ]);

        // Image: file upload takes priority, then URL field, then keep existing
        $image = $request->input('about_image_current', Setting::get('about_image', $this->defaults['about_image']));

        if ($request->hasFile('about_image_upload')) {
            // Delete previously uploaded file (skip external/relative URLs)
            $old = Setting::get('about_image', '');
            if ($old && !str_starts_with($old, '/') && !str_starts_with($old, 'http')) {
                Storage::disk('public')->delete($old);
            }
            $image = $request->file('about_image_upload')->store('about', 'public');
        } elseif ($request->filled('about_image_url')) {
            $image = $request->input('about_image_url');
        }

        // Save all text fields
        foreach ($this->allKeys as $key) {
            if ($key === 'about_image') {
                continue;
            }
            Setting::set($key, $request->input($key, ''));
        }

        Setting::set('about_image', $image);

        return back()->with('success', 'About page content saved successfully.');
    }
}
