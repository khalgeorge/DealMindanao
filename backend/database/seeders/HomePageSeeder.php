<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // SEO / Meta
            'home_meta_title'       => 'DealMindanao - Authentic Mindanao Hardware & Heavy Equipment',
            'home_meta_description' => 'Quality tools and equipment from trusted local sellers — order online, pay offline, delivered with care.',

            // Hero Carousel
            'home_hero_enabled' => '1',
            'home_hero_slides'  => json_encode([
                [
                    'image'             => '/hero1.png',
                    'heading'           => "Discover Authentic\nMindanao Hardware & Heavy Equipment",
                    'heading_highlight' => 'Hardware & Heavy Equipment',
                    'highlight_class'   => 'text-brand-400',
                    'subtext'           => 'Quality tools and equipment from trusted local sellers — order online, pay offline, delivered with care.',
                    'cta_text'          => 'Shop Deals',
                    'cta_url'           => '/shop',
                    'pills'             => 'Power Tools, Home Improvement, Outdoor Gear',
                    'badge1'            => 'Trusted Sellers',
                    'badge2'            => 'Cash on Delivery',
                    'disclaimer'        => 'Order request only. No online payment required. Our team will contact you to confirm.',
                ],
                [
                    'image'             => '/hero2.png',
                    'heading'           => "Quality Hardware\nFrom Local Sellers",
                    'heading_highlight' => 'Hardware',
                    'highlight_class'   => 'text-accent-400',
                    'subtext'           => 'Browse curated deals and support Mindanao entrepreneurs with every purchase.',
                    'cta_text'          => 'Shop Deals',
                    'cta_url'           => '/shop',
                    'pills'             => 'Power Tools, Home Improvement, Outdoor Gear',
                    'badge1'            => 'Trusted Sellers',
                    'badge2'            => 'Cash on Delivery',
                    'disclaimer'        => 'Order request only. No online payment required. Our team will contact you to confirm.',
                ],
            ]),

            // Weekly Highlights
            'home_highlights_enabled'  => '1',
            'home_highlights_badge'    => 'This Week',
            'home_highlights_heading'  => 'Weekly Highlights',
            'home_highlights_subtext'  => 'Handpicked deals and exclusive offers from our trusted local partners across Mindanao.',
            'home_highlights_cta_text' => 'View All Products',
            'home_highlights_cta_url'  => '/shop',

            // Why Shop (Benefits)
            'home_benefits_enabled' => '1',
            'home_benefits_heading' => 'Why Shop on DealMindanao?',
            'home_benefits_subtext' => 'Experience the best of Mindanao with confidence and convenience.',
            'home_benefits_items'   => json_encode([
                ['title' => '100% Authentic', 'description' => 'All products are verified and sourced from trusted local sellers'],
                ['title' => 'Best Deals',      'description' => 'Curated pricing for great value on quality hardware'],
                ['title' => 'Support Local',   'description' => 'Every order supports Mindanao communities'],
                ['title' => 'Easy Shopping',   'description' => 'Order online, we coordinate payment & delivery'],
            ]),

            // What to Expect (Steps)
            'home_steps_enabled'         => '1',
            'home_steps_heading'         => 'What to Expect After You Order',
            'home_steps_subtext'         => 'A seamless journey from checkout to delivery',
            'home_steps_items'           => json_encode([
                ['title' => 'Order Confirmation',   'description' => 'We review your request within 24 hours',         'time_label' => 'Within 24 hours'],
                ['title' => 'Payment Instructions', 'description' => 'We contact you with COD/Bank/GCash options',      'time_label' => 'Next business day'],
                ['title' => 'Order Processing',     'description' => 'We prepare your order and update you',           'time_label' => '1-3 business days'],
                ['title' => 'Delivery & Enjoy',     'description' => 'Items delivered to your door',                   'time_label' => '3-7 business days'],
            ]),
            'home_steps_info_heading'    => 'Need Help?',
            'home_steps_info_text'       => 'Our customer support team is ready to assist you throughout your order journey. Contact us anytime via email or phone for questions about your order.',
            'home_steps_info_link1_text' => 'Visit Help Center',
            'home_steps_info_link1_url'  => '/help',
            'home_steps_info_link2_text' => 'Contact Support',
            'home_steps_info_link2_url'  => '/contact',

            // Call to Action
            'home_cta_enabled'   => '1',
            'home_cta_heading'   => 'Join the Mindanao Movement',
            'home_cta_subtext'   => 'Become a partner and showcase your quality hardware products across Mindanao.',
            'home_cta_btn1_text' => 'Become a Partner',
            'home_cta_btn1_url'  => '/partner',
            'home_cta_btn2_text' => 'Learn More',
            'home_cta_btn2_url'  => '/about',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
