<?php

namespace App\Jobs;

use App\Models\Tenants\QrisTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CleanupExpiredQrisTransactions implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $expiredTransactions = QrisTransaction::query()
                ->where('status', 'pending')
                ->where('expires_at', '<', now())
                ->get();

            $count = 0;
            foreach ($expiredTransactions as $transaction) {
                $transaction->update(['status' => 'expired']);
                $count++;
            }

            $veryOldTransactions = QrisTransaction::query()
                ->where('status', 'expired')
                ->where('expires_at', '<', now()->subDay())
                ->delete();

            Log::info('QRIS transaction cleanup completed', [
                'expired_marked' => $count,
                'old_deleted' => $veryOldTransactions,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to cleanup expired QRIS transactions', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
