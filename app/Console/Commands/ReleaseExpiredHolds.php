<?php

namespace App\Console\Commands;

use App\Models\Hold;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReleaseExpiredHolds extends Command
{
    /**
     *
     * @var string
     */
    protected $signature = 'holds:release-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release expired holds and return reserved quantities to product stock.';

    public function handle()
    {
        $now = Carbon::now();

        // Get candidate holds (status active and expired)
        $candidates = Hold::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->get();

        $count = 0;

        foreach ($candidates as $hold) {
            DB::transaction(function () use ($hold, &$count) {
                $lockedHold = Hold::where('id', $hold->id)->lockForUpdate()->first();
                if (!$lockedHold) return;

                if ($lockedHold->status !== 'active') return;

                $product = Product::where('id', $lockedHold->product_id)->lockForUpdate()->first();
                if ($product) {
                    $product->reserved = max(0, $product->reserved - $lockedHold->quantity);
                    $product->save();
                }

                $lockedHold->status = 'expired';
                $lockedHold->save();

                $count++;
            });
        }

        $this->info("Released {$count} expired hold(s).");

        return 0;
    }
}
