<?php

namespace App\Console\Commands;

use App\Jobs\SendTelegramJob;
use App\Models\ServerShadowsocks;
use App\Models\ServerTrojan;
use App\Models\ServerVmess;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class CheckServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '节点检查任务';

    /**
     * @var ConsoleOutput
     */
    private $_out;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_out = new ConsoleOutput();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->checkOffline();
    }

    /**
     * check offline
     *
     * @return void
     */
    private function checkOffline()
    {
        $shadowFaultNodes = ServerShadowsocks::faultNodeNames();
        $vmessFaultNodes = ServerVmess::faultNodeNames();
        $trojanFaultServers = ServerTrojan::faultNodeNames();

        $faultNodes = array_merge($shadowFaultNodes, $vmessFaultNodes, $trojanFaultServers);
        $faultNodesTotal = count($faultNodes);
        $telegramBotEnable = (bool)config('v2board.telegram_bot_enable', 0);
        if ($faultNodesTotal > 0 && $telegramBotEnable) {
            $message = "📮Hãy kiểm tra máy chủ：\n Máy chủ{$faultNodesTotal} không hoạt động, vui lòng kiểm tra ngay lập tức: \n". join("\n", $faultNodes);
            SendTelegramJob::generateJobWithAdminMessages($message);
        }
        $this->_out->writeln("Tổng số máy chủ không hoạt động: " . $faultNodesTotal);
    }
}