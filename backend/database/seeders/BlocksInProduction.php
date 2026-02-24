<?php

namespace Database\Seeders;

/**
 * Trait BlocksInProduction
 *
 * Apply this trait to any seeder that must NEVER run in the production
 * environment — particularly seeders that truncate tables or insert demo data.
 *
 * Usage:
 *   class MySeeder extends Seeder
 *   {
 *       use BlocksInProduction;
 *
 *       public function run(): void
 *       {
 *           if ($this->guardAgainstProduction()) return;
 *           // ... seeder logic
 *       }
 *   }
 */
trait BlocksInProduction
{
    /**
     * Abort execution when APP_ENV=production.
     *
     * @return bool  true = blocked (caller should return immediately)
     *               false = safe to continue
     */
    protected function guardAgainstProduction(): bool
    {
        if (! app()->environment('production')) {
            return false;
        }

        $class = class_basename(static::class);

        if (isset($this->command)) {
            $this->command->newLine();
            $this->command->error("  ⛔  BLOCKED: {$class} cannot run in APP_ENV=production.");
            $this->command->error('  ⛔  Demo seeders and destructive seeders are disabled in production.');
            $this->command->error('  ⛔  Use targeted content seeders only, or set APP_ENV=local/staging.');
            $this->command->newLine();
        }

        return true;
    }
}
