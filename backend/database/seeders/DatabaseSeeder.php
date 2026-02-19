<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@dealmindanao.ph',
            'password' => bcrypt('password123'),
            'is_admin' => true,
            'email_verified_at' => now()
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => bcrypt('password123'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        // Create categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);

        $fashion = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
        ]);

        $food = Category::create([
            'name' => 'Food & Beverages',
            'slug' => 'food-beverages',
        ]);

        $crafts = Category::create([
            'name' => 'Handicrafts',
            'slug' => 'handicrafts',
        ]);

        // Create a company
        $company = Company::create([
            'name' => 'DealMindanao Marketplace',
            'city' => 'Davao City',
            'contact_email' => 'contact@dealmindanao.ph',
            'contact_phone' => '+63 XXX XXX XXXX',
        ]);

        // Create products
        Product::create([
            'name' => 'Mindanao Coffee Beans',
            'slug' => 'mindanao-coffee-beans',
            'description' => 'Premium arabica coffee beans from the highlands of Bukidnon',
            'price' => 450.00,
            'discount' => 45.00,
            'category_id' => $food->id,
            'company_id' => $company->id,
            'images' => ['/products/coffee.jpg'],
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'name' => 'Durian Delicacies',
            'slug' => 'durian-delicacies',
            'description' => 'Authentic durian candies and pastries from Davao',
            'price' => 350.00,
            'discount' => 0,
            'category_id' => $food->id,
            'company_id' => $company->id,
            'images' => ['/products/durian.jpg'],
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'name' => 'Mindanao Woven Bag',
            'slug' => 'mindanao-woven-bag',
            'description' => 'Handwoven traditional bag from T\'boli artisans',
            'price' => 850.00,
            'discount' => 127.50,
            'category_id' => $crafts->id,
            'company_id' => $company->id,
            'images' => ['/products/woven-bag.jpg'],
            'is_active' => true,
            'is_featured' => false,
        ]);

        Product::create([
            'name' => 'Mandaya Embroidered Shirt',
            'slug' => 'mandaya-embroidered-shirt',
            'description' => 'Traditional Mandaya embroidered shirt',
            'price' => 1200.00,
            'discount' => 240.00,
            'category_id' => $fashion->id,
            'company_id' => $company->id,
            'images' => ['/products/mandaya-shirt.jpg'],
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'name' => 'Davao Pomelo',
            'slug' => 'davao-pomelo',
            'description' => 'Fresh and sweet pomelo from Davao orchards',
            'price' => 250.00,
            'discount' => 0,
            'category_id' => $food->id,
            'company_id' => $company->id,
            'images' => ['/products/pomelo.jpg'],
            'is_active' => true,
            'is_featured' => false,
        ]);

        Product::create([
            'name' => 'Malong Traditional Cloth',
            'slug' => 'malong-traditional-cloth',
            'description' => 'Authentic Maranao malong with traditional patterns',
            'price' => 680.00,
            'discount' => 34.00,
            'category_id' => $crafts->id,
            'company_id' => $company->id,
            'images' => ['/products/malong.jpg'],
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'name' => 'Banana Chips',
            'slug' => 'banana-chips',
            'description' => 'Crispy banana chips from Bukidnon',
            'price' => 180.00,
            'discount' => 0,
            'category_id' => $food->id,
            'company_id' => $company->id,
            'images' => ['/products/banana-chips.jpg'],
            'is_active' => true,
            'is_featured' => false,
        ]);

        Product::create([
            'name' => 'Brass Handicrafts',
            'slug' => 'brass-handicrafts',
            'description' => 'Traditional Maranao brass crafts',
            'price' => 950.00,
            'discount' => 95.00,
            'category_id' => $crafts->id,
            'company_id' => $company->id,
            'images' => ['/products/brass.jpg'],
            'is_active' => true,
            'is_featured' => false,
        ]);

        $this->call([
            PageSeeder::class,
            NavigationSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
