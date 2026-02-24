<?php

namespace Database\Seeders;

use App\Models\RefundSection;
use Illuminate\Database\Seeder;

class RefundSectionSeeder extends Seeder
{
    use BlocksInProduction;

    public function run(): void
    {
        if ($this->guardAgainstProduction()) return;

        RefundSection::truncate();

        $sections = [
            [
                'title'      => 'Return Eligibility',
                'body'       => '<p class="text-gray-600 leading-relaxed mb-4">Items are eligible for return within <strong>48 hours of delivery</strong>. To be eligible for a return, your item must meet the following conditions:</p>
<ul class="list-disc list-inside text-gray-600 space-y-2">
  <li>Item must be unused and in the same condition that you received it</li>
  <li>Item must be in its original packaging</li>
  <li>Item must be accompanied by a receipt or proof of purchase</li>
</ul>',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'title'      => 'Return Process',
                'body'       => '<ol class="list-decimal list-inside text-gray-600 space-y-3">
  <li>Contact our support team at <a href="mailto:hello@dealmindanao.ph" class="text-brand-600 font-semibold hover:underline">hello@dealmindanao.ph</a> to initiate your return</li>
  <li>Provide your order number and reason for the return</li>
  <li>Our team will review your request within 1-2 business days</li>
  <li>If approved, you will receive instructions on how to ship the item back to us</li>
  <li>Once we receive and inspect the item, we will process your refund</li>
</ol>',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'title'      => 'Non-Returnable Items',
                'body'       => '<p class="text-gray-600 leading-relaxed mb-4">The following items cannot be returned:</p>
<ul class="list-disc list-inside text-gray-600 space-y-2">
  <li>Items that have been used or damaged by the customer</li>
  <li>Items that are not in their original packaging</li>
  <li>Items purchased on final sale or with a non-returnable designation</li>
  <li>Items that have been altered or modified</li>
</ul>',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'title'      => 'Refund Timeline',
                'body'       => '<p class="text-gray-600 leading-relaxed">Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. If approved, your refund will be processed within 7–14 business days and automatically applied to your original payment method.</p>',
                'sort_order' => 4,
                'is_active'  => true,
            ],
        ];

        foreach ($sections as $section) {
            RefundSection::create($section);
        }

        $this->command->info('RefundSectionSeeder: ' . count($sections) . ' sections seeded.');
    }
}
