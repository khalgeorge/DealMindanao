<?php

namespace Database\Seeders;

use App\Models\TermsSection;
use Illuminate\Database\Seeder;

class TermsSectionSeeder extends Seeder
{
    use BlocksInProduction;

    public function run(): void
    {
        if ($this->guardAgainstProduction()) return;

        TermsSection::truncate();

        $sections = [
            [
                'title'      => 'Order Confirmation',
                'body'       => '<p class="text-gray-600 leading-relaxed">All orders placed on DealMindanao are subject to confirmation. We reserve the right to accept or decline any order request based on product availability, pricing accuracy, or other factors. Orders are not final until you receive confirmation from our team.</p>',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'title'      => 'Pricing and Availability',
                'body'       => '<p class="text-gray-600 leading-relaxed">Prices displayed on DealMindanao are indicative and may change based on availability at the time of order confirmation. We will notify you of any price changes before finalizing your order. Product availability is subject to change without notice.</p>',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'title'      => 'Payment Arrangements',
                'body'       => '<p class="text-gray-600 leading-relaxed">Payments are arranged offline after order confirmation. DealMindanao coordinates delivery but does not process online payments. Payment methods include cash on delivery (COD), bank transfer, or GCash, as agreed upon during order confirmation.</p>',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'title'      => 'Delivery',
                'body'       => '<p class="text-gray-600 leading-relaxed">DealMindanao coordinates delivery with local partners to ensure timely and secure shipping. Delivery timelines are estimates and may vary based on location and product availability. We are not responsible for delays caused by third-party couriers.</p>',
                'sort_order' => 4,
                'is_active'  => true,
            ],
            [
                'title'      => 'Returns and Refunds',
                'body'       => '<p class="text-gray-600 leading-relaxed">Please refer to our <a href="/refunds" class="text-brand-600 hover:underline font-semibold">Refund &amp; Returns Policy</a> for detailed information on eligible returns and the refund process.</p>',
                'sort_order' => 5,
                'is_active'  => true,
            ],
            [
                'title'      => 'User Responsibilities',
                'body'       => '<p class="text-gray-600 leading-relaxed mb-4">By using DealMindanao, you agree to:</p>
<ul class="list-disc list-inside text-gray-600 space-y-2">
  <li>Provide accurate contact and delivery information</li>
  <li>Respond to order confirmation communications in a timely manner</li>
  <li>Pay for orders as agreed during confirmation</li>
  <li>Not misuse the platform or engage in fraudulent activity</li>
</ul>',
                'sort_order' => 6,
                'is_active'  => true,
            ],
            [
                'title'      => 'Limitation of Liability',
                'body'       => '<p class="text-gray-600 leading-relaxed">DealMindanao acts as a marketplace coordinator and is not responsible for product quality issues beyond our verification process. Our liability is limited to the purchase price of the product in question.</p>',
                'sort_order' => 7,
                'is_active'  => true,
            ],
            [
                'title'      => 'Changes to Terms',
                'body'       => '<p class="text-gray-600 leading-relaxed">We reserve the right to update these Terms of Service at any time. Continued use of DealMindanao after changes constitutes acceptance of the updated terms.</p>',
                'sort_order' => 8,
                'is_active'  => true,
            ],
        ];

        foreach ($sections as $section) {
            TermsSection::create($section);
        }

        $this->command->info('TermsSectionSeeder: ' . count($sections) . ' sections seeded.');
    }
}
