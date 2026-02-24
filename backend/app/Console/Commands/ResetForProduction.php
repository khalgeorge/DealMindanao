<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * One-time pre-launch catalog reset.
 *
 * Deletes ALL development data (products, categories, companies, orders)
 * while preserving user accounts and CMS content.
 *
 * Usage:
 *   php artisan db:reset-for-production            -- dry run (safe, no changes)
 *   CONFIRM_PROD_RESET=true php artisan db:reset-for-production --force  -- execute
 *
 * Guard: after a successful execution the sentinel file
 *   storage/app/.prod-reset-done  is created.
 * The command refuses to run again once that file exists.
 */
class ResetForProduction extends Command
{
    protected $signature = 'db:reset-for-production
                            {--dry-run : Show counts only, make NO changes (default behaviour)}
                            {--force  : Actually execute the reset (requires CONFIRM_PROD_RESET=true)}';

    protected $description = 'One-time pre-launch reset: deletes all dev products/categories/companies/orders. Preserves users and CMS content.';

    // ── Tables cleared completely ──────────────────────────────────────────────
    // Deletion order must respect FK constraints:
    //   order_items → orders (and → products)
    //   products    → categories, companies
    //   addresses   → (users, kept) safe to clear dev addresses
    //   sessions    → no FK, dev sessions only
    private const RESET_TABLES = [
        'order_items',  // FK → orders, products
        'orders',       // FK → users  (users are NOT deleted)
        'addresses',    // FK → users  (users are NOT deleted)
        'products',     // FK → categories, companies
        'categories',
        'companies',
        'deals',        // independent, currently empty
        'sessions',     // PHP/browser sessions, no FK
    ];

    // ── Tables whose AUTO_INCREMENT is reset to 1 after clearing ──────────────
    private const RESET_AI_TABLES = [
        'order_items', 'orders', 'addresses',
        'products', 'categories', 'companies', 'deals', 'sessions',
    ];

    // ── Storage directories whose files are wiped (relative to storage/app/public) ──
    private const MEDIA_DIRS = ['products'];

    // ── Placeholder / system files to NEVER delete ────────────────────────────
    private const PROTECTED_FILENAMES = [
        'no-image-available.png',
        'placeholder.png',
        'no_image.png',
        'default-product.jpg',
        'no-image.jpg',
        '.gitignore',
        '.gitkeep',
    ];

    private const SENTINEL = '.prod-reset-done';

    // ──────────────────────────────────────────────────────────────────────────

    public function handle(): int
    {
        $isDryRun = ! $this->option('force');   // default: dry-run unless --force

        $this->newLine();
        $this->line('╔══════════════════════════════════════════════════════════╗');
        $this->line('║       DealMindanao — Production Catalog Reset Tool       ║');
        $this->line('╚══════════════════════════════════════════════════════════╝');
        $this->newLine();

        // ── 1. Sentinel guard ─────────────────────────────────────────────────
        if (! $isDryRun && Storage::exists(self::SENTINEL)) {
            $this->error('🔒  BLOCKED: This reset has already been executed.');
            $this->line('   Sentinel file found: storage/app/' . self::SENTINEL);
            $this->line('   Remove it manually ONLY if you are absolutely sure you need a second reset.');
            return self::FAILURE;
        }

        // ── 2. Env guard (--force only) ───────────────────────────────────────
        // Use getenv() (not env()) so the var can be passed at runtime without
        // requiring a .env file change: CONFIRM_PROD_RESET=true php artisan ...
        if (! $isDryRun) {
            if (getenv('CONFIRM_PROD_RESET') !== 'true') {
                $this->error('🛑  BLOCKED: env var CONFIRM_PROD_RESET=true is required to execute.');
                $this->line('   Run:  CONFIRM_PROD_RESET=true php artisan db:reset-for-production --force');
                return self::FAILURE;
            }
        }

        // ── 3. Count phase (always runs) ──────────────────────────────────────
        $this->info($isDryRun ? '🔍  DRY RUN — no data will be changed.' : '⚠️   LIVE RUN — data will be permanently deleted.');
        $this->newLine();

        $counts = $this->collectCounts();
        $this->renderCountTable($counts);

        $mediaFiles = $this->countMediaFiles();
        $this->line(sprintf(
            '   📁  Media files to delete: <comment>%d file(s)</comment> in %s',
            $mediaFiles['total'],
            implode(', ', array_map(fn ($d) => "storage/app/public/{$d}", self::MEDIA_DIRS))
        ));
        $this->newLine();

        // ── 4. Early exit on dry-run ──────────────────────────────────────────
        if ($isDryRun) {
            $this->line('  Run with <comment>CONFIRM_PROD_RESET=true php artisan db:reset-for-production --force</comment>');
            $this->line('  to execute the reset.');
            $this->newLine();
            return self::SUCCESS;
        }

        // ── 5. Final confirmation prompt ──────────────────────────────────────
        $this->warn('  ⚠️   ALL rows in the tables above will be PERMANENTLY DELETED.');
        $this->warn('  ⚠️   This cannot be undone (except from backup).');
        $this->newLine();

        if (! $this->confirm('  Have you taken a database backup and are you ready to proceed?', false)) {
            $this->line('  Aborted. No changes made.');
            return self::SUCCESS;
        }

        // ── 6. Take backup ────────────────────────────────────────────────────
        $this->newLine();
        $this->line('  📦  Taking database backup...');
        $backupPath = $this->takeBackup();
        if ($backupPath === null) {
            $this->warn('  ⚠️   Backup failed or mysqldump unavailable — proceeding without backup.');
            $this->warn('      Make sure you have a manual backup before continuing!');
            if (! $this->confirm('  Continue WITHOUT a backup?', false)) {
                $this->line('  Aborted. No changes made.');
                return self::SUCCESS;
            }
        } else {
            $this->line("  ✅  Backup saved to: <comment>{$backupPath}</comment>");
        }

        // ── 7. Execute reset ──────────────────────────────────────────────────
        $this->newLine();
        $this->line('  🗑️   Deleting data...');

        $deleted = $this->executeReset($counts);

        // ── 8. Reset AUTO_INCREMENT ───────────────────────────────────────────
        $this->line('  🔢  Resetting AUTO_INCREMENT...');
        $this->resetAutoIncrement();

        // ── 9. Clean media files ──────────────────────────────────────────────
        $this->line('  🗂️   Cleaning product media files...');
        $filesDeleted = $this->cleanMediaFiles();

        // ── 10. Write sentinel ────────────────────────────────────────────────
        Storage::put(self::SENTINEL, json_encode([
            'executed_at' => now()->toIso8601String(),
            'deleted'     => $deleted,
            'media_files' => $filesDeleted,
            'app_env'     => app()->environment(),
        ], JSON_PRETTY_PRINT));

        Log::channel('stack')->info('[ResetForProduction] Production catalog reset executed', [
            'deleted'     => $deleted,
            'media_files' => $filesDeleted,
        ]);

        // ── 11. Summary ───────────────────────────────────────────────────────
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════════════════╗');
        $this->line('║                  ✅  Reset Complete                      ║');
        $this->line('╚══════════════════════════════════════════════════════════╝');
        $this->newLine();

        $this->renderSummary($deleted, $filesDeleted);

        $this->newLine();
        $this->line('  Sentinel written: <comment>storage/app/' . self::SENTINEL . '</comment>');
        $this->line('  The system is now ready for real product data.');
        $this->newLine();

        return self::SUCCESS;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /** Collect current row counts for all reset tables. */
    private function collectCounts(): array
    {
        $counts = [];
        foreach (self::RESET_TABLES as $table) {
            try {
                $counts[$table] = DB::table($table)->count();
            } catch (\Throwable $e) {
                $counts[$table] = '(error: ' . $e->getMessage() . ')';
            }
        }
        return $counts;
    }

    /** Count media files that would be deleted (excluding protected names). */
    private function countMediaFiles(): array
    {
        $disk  = Storage::disk('public');
        $total = 0;
        $byDir = [];
        foreach (self::MEDIA_DIRS as $dir) {
            $files    = $disk->files($dir);
            $toDelete = array_filter($files, fn ($f) => ! in_array(basename($f), self::PROTECTED_FILENAMES, true));
            $byDir[$dir] = count($toDelete);
            $total += count($toDelete);
        }
        return ['total' => $total, 'by_dir' => $byDir];
    }

    /** Render a table of counts. */
    private function renderCountTable(array $counts): void
    {
        $rows = [];
        $total = 0;
        foreach ($counts as $table => $count) {
            $rows[] = [$table, is_int($count) ? number_format($count) : $count];
            if (is_int($count)) {
                $total += $count;
            }
        }
        $rows[] = ['──────────────', '──────'];
        $rows[] = ['TOTAL rows', number_format($total)];

        $this->table(['Table', 'Rows to delete'], $rows);
    }

    /** Render post-run summary table. */
    private function renderSummary(array $deleted, int $filesDeleted): void
    {
        $rows = [];
        foreach ($deleted as $table => $count) {
            $rows[] = [$table, number_format($count)];
        }
        if ($filesDeleted > 0) {
            $rows[] = ['media files (storage)', $filesDeleted];
        }
        $this->table(['Table / Resource', 'Rows deleted'], $rows);
    }

    /**
     * Take a mysqldump backup via Docker.
     * Returns the backup file path or null on failure.
     */
    private function takeBackup(): ?string
    {
        $timestamp  = now()->format('Y-m-d_His');
        $backupFile = storage_path("app/backups/pre-reset_{$timestamp}.sql.gz");

        @mkdir(dirname($backupFile), 0755, true);

        $dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $dbPort = config('database.connections.mysql.port', '3306');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Try via Docker container first (works inside Docker env), then direct mysqldump
        $dockerCmd = "docker exec dealmindanao-db-1 mysqldump -u{$dbUser} -p{$dbPass} {$dbName} 2>/dev/null | gzip > " . escapeshellarg($backupFile);
        $directCmd = "mysqldump -h{$dbHost} -P{$dbPort} -u{$dbUser} -p{$dbPass} {$dbName} 2>/dev/null | gzip > " . escapeshellarg($backupFile);

        $result = null;
        foreach ([$dockerCmd, $directCmd] as $cmd) {
            exec($cmd, $output, $exitCode);
            if ($exitCode === 0 && file_exists($backupFile) && filesize($backupFile) > 100) {
                $result = $backupFile;
                break;
            }
        }

        return $result;
    }

    /**
     * Execute the actual deletion in FK-safe order.
     * Returns array of [table => rowsDeleted].
     */
    private function executeReset(array $preCounts): array
    {
        $deleted = [];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach (self::RESET_TABLES as $table) {
                $before = $preCounts[$table] ?? 0;
                DB::table($table)->delete();
                $after  = DB::table($table)->count();
                $deleted[$table] = is_int($before) ? ($before - $after) : 0;
                $this->line(sprintf('    %-20s  deleted <comment>%s</comment> row(s)', $table, number_format($deleted[$table])));
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        return $deleted;
    }

    /** Reset AUTO_INCREMENT to 1 on all cleared tables. */
    private function resetAutoIncrement(): void
    {
        foreach (self::RESET_AI_TABLES as $table) {
            try {
                DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = 1");
                $this->line("    ✓  AUTO_INCREMENT reset: <comment>{$table}</comment>");
            } catch (\Throwable $e) {
                $this->line("    ✗  Could not reset AUTO_INCREMENT on {$table}: {$e->getMessage()}");
            }
        }
    }

    /**
     * Delete all non-protected files inside MEDIA_DIRS.
     * Returns total files deleted.
     */
    private function cleanMediaFiles(): int
    {
        $disk  = Storage::disk('public');
        $total = 0;
        foreach (self::MEDIA_DIRS as $dir) {
            $files = $disk->files($dir);
            foreach ($files as $file) {
                if (in_array(basename($file), self::PROTECTED_FILENAMES, true)) {
                    continue;
                }
                $disk->delete($file);
                $total++;
                $this->line('    🗑  deleted media: <comment>' . basename($file) . '</comment>');
            }
        }
        if ($total === 0) {
            $this->line('    ℹ️  No media files to delete.');
        }
        return $total;
    }
}
