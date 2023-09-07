<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStat;
use App\Models\ServerShadowsocks;
use App\Models\ServerTrojan;
use App\Models\ServerVmess;
use App\Models\Ticket;
use App\Models\TrafficServerLog;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{

    /**
     * override
     *
     * @return ResponseFactory|Response
     */
    public function override()
    {
        return response([
            'data' => [
                'month_income' => Order::sumMonthIncome(),
                'month_register_total' => User::countMonthRegister(),
                'ticket_pending_total' => Ticket::countTicketPending(),
                'commission_pending_total' => Order::countCommissionPending(),
                'day_income' => Order::sumDayIncome(),
                'last_month_income' => Order::sumLastMonthIncome()
            ]
        ]);
    }

    /**
     * order
     *
     * @return ResponseFactory|Response
     */
    public function order()
    {
        $orderStats = OrderStat::where(OrderStat::FIELD_RECORD_TYPE, OrderStat::RECORD_TYPE_D)
            ->limit(31)
            ->orderBy(OrderStat::FIELD_RECORD_AT, "DESC")
            ->get();
        $result = [];

        /**
         * @var OrderStat $stat
         */
        foreach ($orderStats as $stat) {
            $date = date('m-d', $stat->getAttribute(OrderStat::FIELD_RECORD_AT));
            array_push($result, [
                'type' => '收款金额',
                'date' => $date,
                'value' => $stat->getAttribute(OrderStat::FIELD_ORDER_AMOUNT) / 100
            ]);

            array_push($result, [
                'type' => '收款笔数',
                'date' => $date,
                'value' => $stat->getAttribute(OrderStat::FIELD_ORDER_COUNT)
            ]);

            array_push($result, [
                'type' => '佣金金额',
                'date' => $date,
                'value' => $stat->getAttribute(OrderStat::FIELD_COMMISSION_AMOUNT) / 100
            ]);

            array_push($result, [
                'type' => '佣金笔数',
                'date' => $date,
                'value' => $stat->getAttribute(OrderStat::FIELD_COMMISSION_COUNT)
            ]);
        }
        return response([
            'data' => array_reverse($result)
        ]);
    }

    /**
     *
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Response
     */
    public function serverRank(Request $request)
    {
        $reqDate = $request->get('date') ?? date('Y-m-d', time());

        $servers = [
            'shadowsocks' => ServerShadowsocks::where(ServerShadowsocks::FIELD_PARENT_ID, 0)->orWhere(ServerShadowsocks::FIELD_PARENT_ID, NULL)->get(),
            'vmess' => ServerVmess::where(ServerVmess::FIELD_PARENT_ID, 0)->orWhere(ServerShadowsocks::FIELD_PARENT_ID, NULL)->get(),
            'trojan' => ServerTrojan::where(ServerVmess::FIELD_PARENT_ID, 0)->orWhere(ServerShadowsocks::FIELD_PARENT_ID, NULL)->get()
        ];

        $timestamp = strtotime($reqDate);
        $statistics = TrafficServerLog::select([
            TrafficServerLog::FIELD_SERVER_ID,
            TrafficServerLog::FIELD_SERVER_TYPE,
            TrafficServerLog::FIELD_U,
            TrafficServerLog::FIELD_D,
            DB::raw('(u+d) as total')
        ])
            ->where(TrafficServerLog::FIELD_LOG_AT, '=', $timestamp)
            ->limit(10)
            ->orderBy('total', "DESC")
            ->get();


        foreach ($statistics as $stats) {
            /**
             * @var TrafficServerLog $stats
             */
            foreach ($servers[$stats->getAttribute(TrafficServerLog::FIELD_SERVER_TYPE)] as $server) {
                /**
                 * @var ServerVmess $server
                 */
                if ($server->getKey() === $stats->getAttribute(TrafficServerLog::FIELD_SERVER_ID)) {
                    $stats['server_name'] = $server->getAttribute(ServerVmess::FIELD_NAME);
                }
            }
            $stats['total'] = floatval(number_format($stats['total'] / 1073741824, 3, '.', ''));
        }
        $statsData = $statistics->toArray();
        array_multisort(array_column($statsData, 'total'), SORT_DESC, SORT_NUMERIC, $statsData);
        return response([
            'data' => $statsData
        ]);
    }
}