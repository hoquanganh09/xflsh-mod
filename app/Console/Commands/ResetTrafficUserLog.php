<?php

namespace App\Console\Commands;

use App\Models\TrafficUserLog;
use Illuminate\Console\Command;

class ResetTrafficUserLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:trafficUserLog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '用户流量日志重置';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        TrafficUserLog::truncate();
    }
}