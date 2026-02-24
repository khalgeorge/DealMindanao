<?php

namespace Database\Seeders;

use App\Models\TrustSafetyItem;
use Illuminate\Database\Seeder;

class TrustSafetyItemSeeder extends Seeder
{
    use BlocksInProduction;

    public function run(): void
    {
        if ($this->guardAgainstProduction()) return;

        TrustSafetyItem::truncate();

        $items = [
            [
                'icon_svg'    => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                'icon_color'  => 'brand',
                'title'       => 'We Verify Product Listings',
                'description' => 'All products listed on DealMindanao are verified by our team to ensure authenticity and quality before they appear on the marketplace.',
                'sort_order'  => 0,
                'is_active'   => true,
            ],
            [
                'icon_svg'    => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                'icon_color'  => 'accent',
                'title'       => 'No Online Payments',
                'description' => 'We do not process online payments. All payment arrangements are coordinated offline after order confirmation, protecting you from online payment fraud.',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'icon_svg'    => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                'icon_color'  => 'brand',
                'title'       => 'Manual Order Review',
                'description' => 'Every order is manually reviewed by our team to verify details and coordinate with sellers before confirming with you.',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'icon_svg'    => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                'icon_color'  => 'accent',
                'title'       => 'Secure Handling of Customer Data',
                'description' => 'Your personal information is handled securely and used only for order processing and delivery coordination. We never sell your data to third parties.',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
            [
                'icon_svg'    => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
                'icon_color'  => 'brand',
                'title'       => 'Support Available for Disputes',
                'description' => 'If you encounter any issues with your order, our support team is available to mediate and resolve disputes between customers and sellers.',
                'sort_order'  => 4,
                'is_active'   => true,
            ],
        ];

        foreach ($items as $item) {
            TrustSafetyItem::create($item);
        }
    }
}
