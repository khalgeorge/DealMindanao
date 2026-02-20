<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        if (Faq::count() > 0) {
            return; // Already seeded
        }

        $faqs = [
            [
                'question'   => 'How does payment work?',
                'answer'     => '<p>DealMindanao operates on an <strong>offline payment model</strong>. After you place your order, our team will reach out to confirm your order and discuss payment options including COD, bank transfer, or GCash. No payment is required at checkout.</p>',
                'sort_order' => 0,
            ],
            [
                'question'   => 'Why do I need to wait for confirmation?',
                'answer'     => '<p>Every order goes through a <strong>manual review process</strong> to verify product availability, confirm your details, and arrange delivery. This usually takes within 24 hours on business days. You\'ll receive an email once your order is confirmed.</p>',
                'sort_order' => 1,
            ],
            [
                'question'   => 'What if my order is delayed?',
                'answer'     => '<p>If your order is taking longer than expected, please <a href="/contact" class="text-brand-600 hover:underline">contact our support team</a> with your order number and we\'ll provide an update as soon as possible.</p>',
                'sort_order' => 2,
            ],
            [
                'question'   => 'How do returns work?',
                'answer'     => '<p>We accept returns within <strong>48 hours</strong> of delivery for items that are unused and in original condition. To initiate a return, contact us with your order number and reason. Please review our <a href="/refunds" class="text-brand-600 hover:underline">full refund policy</a> for details.</p>',
                'sort_order' => 3,
            ],
            [
                'question'   => 'How can I contact support?',
                'answer'     => '<p>You can reach us by email at <a href="mailto:hello@dealmindanao.ph" class="text-brand-600 hover:underline">hello@dealmindanao.ph</a> or through our <a href="/contact" class="text-brand-600 hover:underline">contact form</a>. We aim to respond within 1 business day.</p>',
                'sort_order' => 4,
            ],
            [
                'question'   => 'Do you deliver outside Mindanao?',
                'answer'     => '<p>Our primary focus is Mindanao, but we handle deliveries outside the region on a <strong>case-by-case basis</strong>. Please contact us to discuss delivery options and any additional shipping costs that may apply.</p>',
                'sort_order' => 5,
            ],
            [
                'question'   => 'Are the products authentic?',
                'answer'     => '<p>Yes. Every product listed on DealMindanao is <strong>verified by our team</strong> before being featured. We work directly with trusted local businesses and partners to ensure quality and authenticity.</p>',
                'sort_order' => 6,
            ],
            [
                'question'   => 'Can I cancel my order?',
                'answer'     => '<p>You can cancel your order <strong>before it is confirmed</strong> by our team. Once confirmed, cancellations may not be possible as processing begins immediately. Contact us as soon as possible if you need to cancel.</p>',
                'sort_order' => 7,
            ],
            [
                'question'   => 'How long does delivery take?',
                'answer'     => '<p>Delivery typically takes <strong>3-7 business days</strong> depending on your location within Mindanao. You\'ll receive delivery details during order confirmation.</p>',
                'sort_order' => 8,
            ],
            [
                'question'   => 'Is there a minimum order amount?',
                'answer'     => '<p>No, there is <strong>no minimum order amount</strong>. You can order as little or as much as you need. However, delivery fees may vary based on order size and location.</p>',
                'sort_order' => 9,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_active' => true]));
        }
    }
}
