<?php

namespace App\Console\Commands;

use App\Models\{Block, UserActivity};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Unblock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:unblock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unblock Certain User Based On Param Id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $blockedIps = Block::where('created_at', '<=', now()->subMinute())->get();

        foreach ($blockedIps as $block) {
            cache()->forget("blocked_ip:{$block->ip}");
            Log::info("Unblocked IP: {$block->ip}");
            $block->delete();
        }
    }
}
