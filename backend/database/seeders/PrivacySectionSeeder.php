<?php

namespace Database\Seeders;

use App\Models\PrivacySection;
use Illuminate\Database\Seeder;

class PrivacySectionSeeder extends Seeder
{
    use BlocksInProduction;

    public function run(): void
    {
        if ($this->guardAgainstProduction()) return;

        PrivacySection::truncate();

        $sections = [
            [
                'title'      => 'Information We Collect',
                'body'       => '<p class="text-gray-600 leading-relaxed mb-4">We collect only necessary personal data to process orders and arrange delivery:</p>
<ul class="list-disc list-inside text-gray-600 space-y-2">
<li>Name and contact information (phone number, email address)</li>
<li>Delivery address</li>
<li>Order details and purchase history</li>
<li>Communication preferences</li>
</ul>',
                'sort_order' => 0,
                'is_active'  => true,
            ],
            [
                'title'      => 'How We Use Your Information',
                'body'       => '<p class="text-gray-600 leading-relaxed mb-4">Your personal information is used for the following purposes:</p>
<ul class="list-disc list-inside text-gray-600 space-y-2">
<li>Processing and confirming your orders</li>
<li>Coordinating delivery with our partners</li>
<li>Communicating order status updates</li>
<li>Providing customer support</li>
<li>Improving our services</li>
</ul>',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'title'      => 'Payment Information',
                'body'       => '<p class="text-gray-600 leading-relaxed">We do not store payment information. All payment transactions are coordinated offline after order confirmation. We do not process or store credit card details or bank account information.</p>',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'title'      => 'Data Security',
                'body'       => '<p class="text-gray-600 leading-relaxed">We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. Your data is handled securely and stored on protected servers.</p>',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'title'      => 'Sharing Your Information',
                'body'       => '<p class="text-gray-600 leading-relaxed mb-4">We never sell your data to third parties. Your information may be shared only in the following circumstances:</p>
<ul class="list-disc list-inside text-gray-600 space-y-2">
<li>With delivery partners to fulfill your order</li>
<li>With sellers to coordinate product preparation (name and contact only)</li>
<li>When required by law or legal process</li>
</ul>',
                'sort_order' => 4,
                'is_active'  => true,
            ],
            [
                'title'      => 'Your Rights',
                'body'       => '<p class="text-gray-600 leading-relaxed mb-4">You have the right to:</p>
<ul class="list-disc list-inside text-gray-600 space-y-2">
<li>Access your personal data we hold</li>
<li>Request correction of inaccurate information</li>
<li>Request deletion of your data (subject to legal obligations)</li>
<li>Opt out of marketing communications</li>
</ul>',
                'sort_order' => 5,
                'is_active'  => true,
            ],
            [
                'title'      => 'Cookies and Tracking',
                'body'       => '<p class="text-gray-600 leading-relaxed">We use cookies to enhance your browsing experience and remember your preferences. You can disable cookies in your browser settings, though this may affect site functionality.</p>',
                'sort_order' => 6,
                'is_active'  => true,
            ],
            [
                'title'      => 'Changes to This Policy',
                'body'       => '<p class="text-gray-600 leading-relaxed">We may update this Privacy Policy from time to time. We will notify you of any significant changes by posting the new policy on this page with an updated revision date.</p>',
                'sort_order' => 7,
                'is_active'  => true,
            ],
        ];

        foreach ($sections as $section) {
            PrivacySection::create($section);
        }
    }
}
