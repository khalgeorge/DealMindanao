<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'label' => 'Home',
                'url' => '/',
                'location' => 'header',
                'position' => 1,
                'is_active' => true,
            ],
            [
                'label' => 'Shop',
                'url' => '/shop',
                'location' => 'header',
                'position' => 2,
                'is_active' => true,
            ],
            [
                'label' => 'About',
                'url' => '/about',
                'location' => 'header',
                'position' => 3,
                'is_active' => true,
            ],
            [
                'label' => 'Contact',
                'url' => '/contact',
                'location' => 'header',
                'position' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            NavigationItem::updateOrCreate(
                ['label' => $item['label'], 'location' => $item['location']],
                $item
            );
        }
    }
}
