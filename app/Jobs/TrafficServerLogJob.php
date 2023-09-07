<?php

namespace App\Jobs;

use App\Models\TrafficServerLog;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class TrafficServerLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $serverId;
    protected $serverType;
    protected $ru;
    protected $rd;

    public $tries = 3;
    public $timeout = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ru, $rd, $serverId, $serverType)
    {
        $this->onQueue('traffic_server_log');
        $this->ru = $ru;
        $this->rd = $rd;
        $this->serverId = $serverId;
        $this->serverType = $serverType;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        $date = date('Y-m-d');
        $timestamp = strtotime($date);

        /**
         * @var TrafficServerLog $serverLog
         */
        $trafficServerLog = TrafficServerLog::where(TrafficServerLog::FIELD_LOG_AT, '=', $timestamp)
            ->where(TrafficServerLog::FIELD_SERVER_ID, $this->serverId)
            ->first();

        if ($trafficServerLog !== null) {
            $trafficServerLog->addTraffic($this->ru, $this->rd);
        } else {
            $trafficServerLog = new TrafficServerLog();
            $trafficServerLog->addTraffic($this->ru, $this->rd);
            $trafficServerLog->setAttribute(TrafficServerLog::FIELD_SERVER_TYPE, $this->serverType);
            $trafficServerLog->setAttribute(TrafficServerLog::FIELD_SERVER_ID, $this->serverId);
            $trafficServerLog->setAttribute(TrafficServerLog::FIELD_LOG_AT, $timestamp);
            $trafficServerLog->setAttribute(TrafficServerLog::FIELD_LOG_DATE, $date);
        }

        if (!$trafficServerLog->save()) {
            throw new Exception("server save failed");
        }
    }
}