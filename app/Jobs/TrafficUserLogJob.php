<?php

namespace App\Jobs;

use App\Models\TrafficUserLog;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class TrafficUserLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $u;
    protected $d;
    protected $userId;

    public $tries = 3;
    public $timeout = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($u, $d, $userId)
    {
        $this->onQueue('traffic_user_log');
        $this->u = $u;
        $this->d = $d;
        $this->userId = $userId;
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
         * @var TrafficUserLog
         */
        $trafficUserLog = TrafficUserLog::where(TrafficUserLog::FIELD_LOG_AT, '=', $timestamp)
            ->where(TrafficUserLog::FIELD_USER_ID, $this->userId)
            ->first();

        if ($trafficUserLog !== null) {
            $trafficUserLog->addTraffic($this->u, $this->d);
        } else {
            $trafficUserLog = new TrafficUserLog();
            $trafficUserLog->addTraffic($this->u, $this->d);
            $trafficUserLog->setAttribute(TrafficUserLog::FIELD_USER_ID, $this->userId);
            $trafficUserLog->setAttribute(TrafficUserLog::FIELD_LOG_AT, $timestamp);
            $trafficUserLog->setAttribute(TrafficUserLog::FIELD_LOG_DATE, $date);
        }

        if (!$trafficUserLog->save()) {
            throw new Exception("server save failed");
        }

    }

}