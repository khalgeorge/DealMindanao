<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        // Featured products (dynamic, from products table)
        $featuredProducts = Product::with(['category', 'supplier', 'brand'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->take(8)
            ->get();

        $categories = Category::withCount('products')->get();

        // Home page content from settings
        $hp = Setting::getMany([
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
        ]);

        // Decode JSON fields with fallback defaults
        $hp['home_hero_slides']    = json_decode($hp['home_hero_slides'] ?? '[]', true) ?: $this->defaultSlides();
        $hp['home_benefits_items'] = json_decode($hp['home_benefits_items'] ?? '[]', true) ?: [];
        $hp['home_steps_items']    = json_decode($hp['home_steps_items'] ?? '[]', true) ?: [];

        // Resolve stored image paths to URLs for hero slides
        foreach ($hp['home_hero_slides'] as &$slide) {
            $img = $slide['image'] ?? '';
            $slide['image_url'] = ($img && !str_starts_with($img, '/') && !str_starts_with($img, 'http'))
                ? Storage::url($img)
                : $img;
        }
        unset($slide);

        return view('home', compact('featuredProducts', 'categories', 'hp'));
    }

    private function defaultSlides(): array
    {
        $base = [
            'pills' => 'Power Tools, Home Improvement, Outdoor Gear',
            'badge1' => 'Trusted Sellers', 'badge2' => 'Cash on Delivery',
            'disclaimer' => 'Order request only. No online payment required. Our team will contact you to confirm.',
            'cta_text' => 'Shop Deals', 'cta_url' => '/shop',
        ];
        return [
            array_merge($base, ['image' => '/hero1.png', 'image_url' => '/hero1.png', 'heading' => "Discover Authentic\nMindanao Hardware & Heavy Equipment", 'heading_highlight' => 'Hardware & Heavy Equipment', 'highlight_class' => 'text-brand-400', 'subtext' => 'Quality tools and equipment from trusted local sellers — order online, pay offline, delivered with care.']),
            array_merge($base, ['image' => '/hero2.png', 'image_url' => '/hero2.png', 'heading' => "Quality Hardware\nFrom Local Sellers", 'heading_highlight' => 'Hardware', 'highlight_class' => 'text-accent-400', 'subtext' => 'Browse curated deals and support Mindanao entrepreneurs with every purchase.']),
        ];
    }
}

