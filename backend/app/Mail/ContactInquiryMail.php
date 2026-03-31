<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): self
    {
        $subjectLabels = [
            'general'           => 'General Inquiry',
            'order_status'      => 'Order Status',
            'payment_delivery'  => 'Payment & Delivery',
            'returns'           => 'Returns & Refunds',
            'partner'           => 'Partnership',
            'report'            => 'Report an Issue',
        ];

        $subject = $subjectLabels[$this->data['subject'] ?? 'general'] ?? 'Contact Form';

        return $this->subject("[DealMindanao] {$subject} – {$this->data['name']}")
            ->replyTo($this->data['email'], $this->data['name'])
            ->view('emails.contact-inquiry');
    }
}
