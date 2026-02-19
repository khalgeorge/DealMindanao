<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // General
            'platform_name'         => 'DealMindanao Marketplace',
            'support_email'         => 'support@dealmindanao.ph',
            'header_logo'           => '',
            'footer_logo'           => '',
            'maintenance_mode'      => '0',

            // Regional
            'currency'              => 'PHP',
            'regions'               => json_encode([
                'davao',
                'northern_mindanao',
                'zamboanga',
                'soccsksargen',
            ]),

            // Notifications – SMTP (seed from .env if present, else placeholders)
            'smtp_host'             => env('MAIL_HOST', 'smtp.postmarkapp.com'),
            'smtp_port'             => env('MAIL_PORT', '587'),
            'order_pdf_copy'        => '1',

            // Notifications – SMS
            'sms_api_key'           => '',
            'notify_admin_order'    => '1',
            'notify_customer_order' => '1',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
