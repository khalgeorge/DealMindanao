<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about',
                'title' => 'About',
                'subtitle' => 'Who we are',
                'body' => "DealMindanao connects local customers with trusted Mindanao partners. Browse deals, place requests, and pay offline.",
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact',
                'subtitle' => 'Get in touch',
                'body' => "Email: admin@dealmindanao.com\nPhone: +63 900 000 0000\nAddress: Mindanao, Philippines",
            ],
            [
                'slug' => 'terms',
                'title' => 'Terms and Conditions',
                'subtitle' => 'Please read carefully',
                'body' => "Orders are confirmed offline. Prices and availability may change without notice.",
            ],
            [
                'slug' => 'copyrights',
                'title' => 'Copyrights',
                'subtitle' => 'Rights and ownership',
                'body' => "All content on DealMindanao is owned by its respective partners unless otherwise stated.",
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'subtitle' => $page['subtitle'],
                    'body' => $page['body'],
                ]
            );
        }
    }
}
