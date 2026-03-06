<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanDemoProducts extends Command
{
    protected $signature = 'products:clean-demo
                            {--dry-run : List what would be deleted without actually deleting}
                            {--force  : Skip interactive confirmation (use in CI/scripts)}';

    protected $description = 'Detect and remove seeder/demo/test products from the database.
                             Products referenced by orders are always protected.';

    public function handle(): int
    {
        // ─── Production guard ─────────────────────────────────────────────────
        if (app()->environment('production') && ! $this->option('force')) {
            $confirmed = $this->confirm(
                '⚠️  You are running this in PRODUCTION. Are you absolutely sure you want to delete products?',
                false
            );

            if (! $confirmed) {
                $this->info('Aborted. No changes made.');
                return self::SUCCESS;
            }
        }

        $isDryRun = $this->option('dry-run');

        $this->line('');
        $this->info('─────────────────────────────────────────────────────────');
        $this->info('  DealMindanao — Demo Product Cleanup');
        $this->info('─────────────────────────────────────────────────────────');
        $this->line('');

        // Product IDs referenced by orders (must never be deleted).
        $protectedIds = \App\Models\OrderItem::pluck('product_id')->unique()->values()->toArray();

        $this->line('  Scanning products…');
        $products = Product::with('supplier')->get();

        $demoProducts      = [];
        $protectedDemo     = [];
        $suspectProducts   = [];

        foreach ($products as $product) {
            $isProtected = in_array($product->id, $protectedIds, true);

            if ($product->isDemoProduct()) {
                if ($isProtected) {
                    $protectedDemo[] = $product;
                } else {
                    $demoProducts[] = $product;
                }
            } elseif ($this->looksLikeTestProduct($product)) {
                $suspectProducts[] = $product;
            }
        }

        $this->line('');

        // ─── Show protected demo products ────────────────────────────────────
        if (! empty($protectedDemo)) {
            $this->warn('  ⚠  The following demo products have ORDER references and will NOT be deleted:');
            foreach ($protectedDemo as $p) {
                $this->line("     [ID {$p->id}] {$p->name}  (supplier: {$p->supplier?->name})");
            }
            $this->line('');
        }

        // ─── Show safe-to-delete demo products ───────────────────────────────
        if (empty($demoProducts)) {
            $this->info('  ✓  No demo/seeder products found that can be safely deleted.');
        } else {
            $this->line('  The following ' . count($demoProducts) . ' demo product(s) will be deleted:');
            foreach ($demoProducts as $p) {
                $images = implode(', ', $p->images ?? []);
                $this->line("     [ID {$p->id}] {$p->name}  | images: {$images}");
            }
        }

        $this->line('');

        // ─── Show suspect (manually created test) products ───────────────────
        if (! empty($suspectProducts)) {
            $this->warn('  ⚠  The following products look like manual test entries (review manually):');
            foreach ($suspectProducts as $p) {
                $inOrders = in_array($p->id, $protectedIds, true) ? ' [HAS ORDERS - protected]' : '';
                $this->line("     [ID {$p->id}] {$p->name}  (supplier: {$p->supplier?->name}){$inOrders}");
            }
            $this->line('');
            $this->line('  Run  php artisan products:clean-demo  again and enter their IDs to delete manually,');
            $this->line('  or delete them via the Admin → Products panel.');
        }

        if (empty($demoProducts)) {
            $this->line('');
            return self::SUCCESS;
        }

        // ─── Dry-run mode ─────────────────────────────────────────────────────
        if ($isDryRun) {
            $this->line('');
            $this->info('  [dry-run] No changes made. Remove --dry-run to delete.');
            return self::SUCCESS;
        }

        // ─── Confirmation ─────────────────────────────────────────────────────
        if (! $this->option('force')) {
            $confirmed = $this->confirm(
                '  Delete ' . count($demoProducts) . ' demo product(s)? This cannot be undone.',
                false
            );

            if (! $confirmed) {
                $this->info('  Aborted. No changes made.');
                return self::SUCCESS;
            }
        }

        // ─── Delete ───────────────────────────────────────────────────────────
        $deletedCount = 0;
        $freedFiles   = 0;

        foreach ($demoProducts as $product) {
            // Remove any storage files that are only referenced by this product.
            foreach ($product->images ?? [] as $imagePath) {
                // Only delete files in /storage/products/ (uploaded files, not seeder placeholders)
                if (str_starts_with($imagePath, '/storage/products/')) {
                    $storagePath = str_replace('/storage/', '', $imagePath);

                    // Make sure no other product references this file.
                    $refCount = Product::where('id', '!=', $product->id)
                        ->whereJsonContains('images', $imagePath)
                        ->count();

                    if ($refCount === 0 && Storage::disk('public')->exists($storagePath)) {
                        Storage::disk('public')->delete($storagePath);
                        $freedFiles++;
                    }
                }
            }

            $product->delete();
            $this->line("  ✓ Deleted [ID {$product->id}] {$product->name}");
            $deletedCount++;
        }

        $this->line('');
        $this->info("  Done. Deleted {$deletedCount} product(s), freed {$freedFiles} storage file(s).");
        $this->line('');

        return self::SUCCESS;
    }

    /**
     * Secondary check for products that look like manual test entries
     * but do NOT match the primary isDemoProduct() heuristic.
     */
    private function looksLikeTestProduct(Product $product): bool
    {
        // Very short or numeric-only names (e.g. "test1", "aaa")
        if (strlen(trim($product->name)) <= 4) {
            return true;
        }

        // Generic filler descriptions
        $fillerDescriptions = ['test', 'testing', 'asdf', 'qwerty', '1234', 'lorem ipsum'];
        $desc = strtolower(trim($product->description ?? ''));
        foreach ($fillerDescriptions as $filler) {
            if ($desc === $filler || str_starts_with($desc, $filler)) {
                return true;
            }
        }

        return false;
    }
}
