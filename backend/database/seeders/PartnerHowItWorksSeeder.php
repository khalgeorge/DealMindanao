<?php

namespace Database\Seeders;

use App\Models\PartnerHowItWorksStep;
use Illuminate\Database\Seeder;

class PartnerHowItWorksSeeder extends Seeder
{
    public function run(): void
    {
        if (PartnerHowItWorksStep::count() > 0) {
            $this->command->info('Partner How It Works steps already seeded. Skipping.');
            return;
        }

        $steps = [
            ['step_text' => 'Apply to become a partner',                        'sort_order' => 0, 'is_active' => true],
            ['step_text' => 'We onboard and list your products',                 'sort_order' => 1, 'is_active' => true],
            ['step_text' => 'You fulfill orders, we help coordinate delivery',   'sort_order' => 2, 'is_active' => true],
        ];

        foreach ($steps as $step) {
            PartnerHowItWorksStep::create($step);
        }

        $this->command->info('Partner How It Works: 3 default steps seeded.');
    }
}
