<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

/**
 * Ensures the real GEEANN Hardware Trading supplier record exists.
 *
 * Safe to run on production — no destructive operations.
 *
 * Usage:
 *   docker-compose exec backend php artisan db:seed --class=GeeannSeeder
 */
class GeeannSeeder extends Seeder
{
    public function run(): void
    {
        $supplier = Supplier::updateOrCreate(
            ['name' => 'GEEANN Hardware Trading'],
            [
                'is_verified' => true,
                'is_active'   => true,
            ]
        );

        $this->command->info(
            ($supplier->wasRecentlyCreated ? '✅ Created' : '✅ Verified')
            . ' — GEEANN Hardware Trading (id=' . $supplier->id . ')'
        );
    }
}
