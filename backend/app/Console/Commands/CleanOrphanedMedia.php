<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanOrphanedMedia extends Command
{
    protected $signature = 'media:clean-orphans
                            {--dry-run : List orphaned files without deleting them}
                            {--force  : Skip interactive confirmation}';

    protected $description = 'Scan product image storage and remove files not linked to any product.
                             The default placeholder image is always preserved.';

    /** Filenames that must never be deleted regardless of DB references. */
    private const PROTECTED_FILENAMES = [
        'no-image-available.png',
        'placeholder.png',
        'default-product.png',
        'no-image.png',
    ];

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        $this->line('');
        $this->info('─────────────────────────────────────────────────────────');
        $this->info('  DealMindanao — Orphaned Product Media Cleanup');
        $this->info('─────────────────────────────────────────────────────────');
        $this->line('');

        // ─── Gather all files in storage/app/public/products/ ────────────────
        $storageDisk = Storage::disk('public');
        $allFiles    = $storageDisk->files('products');

        if (empty($allFiles)) {
            $this->info('  No files found in products/ storage. Nothing to clean.');
            return self::SUCCESS;
        }

        $this->line("  Found " . count($allFiles) . " file(s) in products/ storage.");

        // ─── Build set of all image paths currently referenced in products ────
        // DB stores paths as:  /storage/products/filename.jpg
        // Storage disk stores: products/filename.jpg
        // We normalise to the storage-relative path (without leading /).
        $referencedPaths = collect(
            Product::whereNotNull('images')->pluck('images')
        )
        ->flatten()
        ->filter()
        ->map(fn ($path) => ltrim(str_replace('/storage/', '', $path), '/'))
        ->unique()
        ->toArray();

        // ─── Detect orphans ───────────────────────────────────────────────────
        $orphans = [];

        foreach ($allFiles as $file) {
            $basename = basename($file);

            // Never delete protected placeholder files.
            if (in_array($basename, self::PROTECTED_FILENAMES, true)) {
                continue;
            }

            if (! in_array($file, $referencedPaths, true)) {
                $orphans[] = $file;
            }
        }

        $this->line('');

        if (empty($orphans)) {
            $this->info('  ✓  No orphaned files found. Storage is clean.');
            return self::SUCCESS;
        }

        $this->warn('  The following ' . count($orphans) . ' orphaned file(s) are not linked to any product:');
        foreach ($orphans as $file) {
            $size = $storageDisk->size($file);
            $this->line(sprintf('     %s  (%s)', $file, $this->formatBytes($size)));
        }

        $this->line('');

        if ($isDryRun) {
            $this->info('  [dry-run] No files deleted. Remove --dry-run to delete.');
            return self::SUCCESS;
        }

        // ─── Confirmation ─────────────────────────────────────────────────────
        if (! $this->option('force')) {
            $confirmed = $this->confirm(
                '  Permanently delete ' . count($orphans) . ' orphaned file(s)?',
                false
            );

            if (! $confirmed) {
                $this->info('  Aborted. No files deleted.');
                return self::SUCCESS;
            }
        }

        // ─── Delete ───────────────────────────────────────────────────────────
        $deletedCount = 0;
        $totalBytes   = 0;

        foreach ($orphans as $file) {
            $totalBytes += $storageDisk->size($file);

            if ($storageDisk->delete($file)) {
                $this->line("  ✓ Deleted: {$file}");
                $deletedCount++;
            } else {
                $this->error("  ✗ Failed to delete: {$file}");
            }
        }

        $this->line('');
        $this->info("  Done. Deleted {$deletedCount} file(s), freed {$this->formatBytes($totalBytes)}.");
        $this->line('');

        return self::SUCCESS;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1_048_576) {
            return round($bytes / 1_048_576, 2) . ' MB';
        }

        if ($bytes >= 1_024) {
            return round($bytes / 1_024, 1) . ' KB';
        }

        return $bytes . ' B';
    }
}
