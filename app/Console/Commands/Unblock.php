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
    private $block_duration;
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->block_duration = (int) env('BLOCK_DURATION');
        $blockedIps = Block::where('created_at', '<=', now()->subHours($this->block_duration))->get();

        foreach ($blockedIps as $block) {
            cache()->forget("blocked_ip:{$block->ip}");
            Log::info("Unblocked IP: {$block->ip}");
            $block->delete();
        }
    }
}
