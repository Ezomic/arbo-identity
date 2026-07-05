<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\RetentionRun;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class RetentionCleanupCommand extends Command
{
    protected $signature = 'retention:cleanup {--dry-run : Preview without deleting}';

    protected $description = 'Delete identity records past their legal retention period (NEN 7513 5yr activity logs)';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $startedAt = CarbonImmutable::now();
        $counts = [];

        // NEN 7513: activity logs minimum 5-year retention; delete after that.
        $expiredLogs = ActivityLog::query()
            ->where('created_at', '<', now()->subYears(5))
            ->count();

        $counts['activity_logs_deleted'] = $expiredLogs;
        $this->info("Activity log entries past 5-year NEN 7513 retention: {$expiredLogs}");

        if (! $dryRun) {
            ActivityLog::query()->where('created_at', '<', now()->subYears(5))->delete();
        }

        RetentionRun::create([
            'command' => 'retention:cleanup',
            'dry_run' => $dryRun,
            'counts' => $counts,
            'started_at' => $startedAt,
            'finished_at' => CarbonImmutable::now(),
        ]);

        $this->info($dryRun ? 'Dry run — no changes made.' : 'Retention cleanup complete.');

        return self::SUCCESS;
    }
}
