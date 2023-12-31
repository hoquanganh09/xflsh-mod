<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserChangePassword;
use App\Http\Requests\User\UserTransfer;
use App\Http\Requests\User\UserUpdate;
use App\Models\Plan;
use App\Models\TrafficServerLog;
use App\Models\TrafficUserLog;
use App\Models\User;
use App\Utils\Helper;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    /**
     * logout
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        return response([
            'data' => true
        ]);
    }

    /**
     * change password
     *
     * @param UserChangePassword $request
     * @return ResponseFactory|Response
     */
    public function changePassword(UserChangePassword $request)
    {
        $sessionId = $request->session()->get('id');
        $reqOldPassword = $request->input('old_password');
        $reqNewPassword = $request->input('new_password');
        /**
         * @var User $user
         */
        $user = User::find($sessionId);
        if ($user === null) {
            abort(500, __('The user does not exist'));
        }

        if (!Helper::multiPasswordVerify(
            $user->getAttribute(User::FIELD_PASSWORD_ALGO),
            $user->getAttribute(User::FIELD_PASSWORD_SALT),
            $reqOldPassword, $user->getAttribute(User::FIELD_PASSWORD))) {
            abort(500, __('The old password is wrong'));
        }

        $user->setAttribute(User::FIELD_PASSWORD, password_hash($reqNewPassword, PASSWORD_DEFAULT));
        $user->setAttribute(User::FIELD_PASSWORD_ALGO, NULL);
        $user->setAttribute(User::FIELD_PASSWORD_SALT, NULL);
        if (!$user->save()) {
            abort(500, __('Save failed'));
        }
        $request->session()->flush();
        return response([
            'data' => true
        ]);
    }

    /**
     * info
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function info(Request $request)
    {
        $sessionId = $request->session()->get('id');
        /**
         * @var User $user
         */
        $user = User::find($sessionId);
        if ($user == null) {
            abort(500, __('The user does not exist'));
        }

        $data = [
            User::FIELD_EMAIL => $user->getAttribute(User::FIELD_EMAIL),
            User::FIELD_LAST_LOGIN_AT => $user->getAttribute(User::FIELD_LAST_LOGIN_AT),
            User::FIELD_CREATED_AT => $user->getAttribute(User::FIELD_CREATED_AT),
            User::FIELD_BANNED => $user->getAttribute(User::FIELD_BANNED),
            User::FIELD_REMIND_TRAFFIC => $user->getAttribute(User::FIELD_REMIND_TRAFFIC),
            User::FIELD_REMIND_EXPIRE => $user->getAttribute(User::FIELD_REMIND_EXPIRE),
            User::FIELD_EXPIRED_AT => $user->getAttribute(User::FIELD_EXPIRED_AT),
            User::FIELD_BALANCE => $user->getAttribute(User::FIELD_BALANCE),
            User::FIELD_COMMISSION_BALANCE => $user->getAttribute(User::FIELD_COMMISSION_BALANCE),
            User::FIELD_PLAN_ID => $user->getAttribute(User::FIELD_PLAN_ID),
            User::FIELD_DISCOUNT => $user->getAttribute(User::FIELD_DISCOUNT),
            User::FIELD_COMMISSION_RATE => $user->getAttribute(User::FIELD_COMMISSION_RATE),
            User::FIELD_TELEGRAM_ID => $user->getAttribute(User::FIELD_TELEGRAM_ID),
        ];

        return response([
            'data' => $data
        ]);
    }

    /**
     * stat
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function stat(Request $request)
    {
        $sessionId = $request->session()->get('id');
        /**
         * @var User $user
         */
        $user = User::find($sessionId);
        if ($user == NULL) {
            abort(500, __('user.user.info.user_not_exist'));
        }

        $stat = [
            $user->countUnpaidOrders(),
            $user->countUnprocessedTickets(),
            $user->countInvitedUsers()
        ];

        return response([
            'data' => $stat
        ]);
    }

    /**
     * subscribe
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function subscribe(Request $request)
    {
        $sessionId = $request->session()->get('id');
        /**
         * @var User $user
         */
        $user = User::find($sessionId);

        if ($user === null) {
            abort(500, __('The user does not exist'));
        }

        $plan = null;
        if ($user->getAttribute(User::FIELD_PLAN_ID) > 0) {
            if ($user->plan() === null) {
                abort(500, __('Subscription plan does not exist'));
            }
            $plan = $user->plan();
        }
        $subscribeUrl = Helper::getSubscribeHost() . "/api/v1/client/subscribe?token={$user->getAttribute(User::FIELD_TOKEN)}";

        $data = [
            "subscribe_url" => $subscribeUrl,
            "plan" => $user->plan(),
            'reset_day' => $user->getResetDay(),
            'is_available' => $user->isAvailable(),
            User::FIELD_ID => $user->getKey(),
            User::FIELD_PLAN_ID => $user->getAttribute(User::FIELD_PLAN_ID),
            User::FIELD_TOKEN => $user->getAttribute(User::FIELD_TOKEN),
            User::FIELD_EXPIRED_AT => $user->getAttribute(User::FIELD_EXPIRED_AT),
            User::FIELD_U => $user->getAttribute(User::FIELD_U),
            User::FIELD_D => $user->getAttribute(User::FIELD_D),
            Plan::FIELD_TRANSFER_ENABLE_VALUE => $plan ? $plan->getAttribute(Plan::FIELD_TRANSFER_ENABLE_VALUE) : null,
            Plan::FIELD_TIME_LIMIT => $plan ? (bool)$plan->getAttribute(Plan::FIELD_TIME_LIMIT) : false,
            Plan::FIELD_START_SEC => $plan ? $plan->getAttribute(Plan::FIELD_START_SEC) : null,
            Plan::FIELD_END_SEC => $plan ? $plan->getAttribute(Plan::FIELD_END_SEC) : null,
            User::FIELD_EMAIL => $user->getAttribute(User::FIELD_EMAIL),
        ];

        return response([
            "data" => $data
        ]);
    }

    /**
     * resetSecurity
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function resetSecurity(Request $request)
    {
        $sessionId = $request->session()->get('id');
        /**
         * @var User $user
         */
        $user = User::find($sessionId);
        if ($user === null) {
            abort(500, __('The user does not exist'));
        }

        $user->setAttribute(User::FIELD_UUID, Helper::guid(true));
        $user->setAttribute(User::FIELD_TOKEN, Helper::guid());

        if (!$user->save()) {
            abort(500, __('Reset failed'));
        }

        return response([
            'data' => config('v2board.subscribe_url', config('v2board.app_url', env('APP_URL'))) . '/api/v1/client/subscribe?token=' . $user->getAttribute(User::FIELD_TOKEN)
        ]);
    }

    /**
     * update
     *
     * @param UserUpdate $request
     * @return ResponseFactory|Response
     */
    public function update(UserUpdate $request)
    {
        $sessionId = $request->session()->get('id');
        $reqRemindExpire = $request->input("remind_expire");
        $reqRemindTraffic = $request->input("remind_traffic");
        /**
         * @var User $user
         */
        $user = User::find($sessionId);
        if ($user === null) {
            abort(500, __('The user does not exist'));
        }

        if ($reqRemindExpire !== null) {
            $user->setAttribute(User::FIELD_REMIND_EXPIRE, (int)$reqRemindExpire);
        }

        if ($reqRemindTraffic !== null) {
            $user->setAttribute(User::FIELD_REMIND_TRAFFIC, (int)$reqRemindTraffic);
        }

        if (!$user->save()) {
            abort(500, __('Save failed'));
        }

        return response([
            'data' => true
        ]);
    }

    /**
     * transfer
     *
     * @param UserTransfer $request
     * @return ResponseFactory|Response
     */
    public function transfer(UserTransfer $request)
    {
        $sessionId = $request->session()->get('id');
        $reqTransferAmount = $request->input('transfer_amount');
        /**
         * @var User $user
         */
        $user = User::find($sessionId);
        if ($user === null) {
            abort(500, __('The user does not exist'));
        }

        if ($reqTransferAmount > $user->getAttribute(User::FIELD_COMMISSION_BALANCE)) {
            abort(500, __('Insufficient commission balance'));
        }

        $user->setAttribute(User::FIELD_COMMISSION_BALANCE, $user->getAttribute(User::FIELD_COMMISSION_BALANCE) - $reqTransferAmount);
        $user->setAttribute(User::FIELD_BALANCE, $user->getAttribute(User::FIELD_BALANCE) + $reqTransferAmount);

        if (!$user->save()) {
            abort(500, __('Transfer failed'));
        }
        return response([
            'data' => true
        ]);
    }


    /**
     *  fetch traffic log
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function trafficLogs(Request $request)
    {
        $reqCurrent = (int)$request->input('current') ? $request->input('current') : 1;
        $reqPageSize = (int)$request->input('pageSize') >= 10 ? $request->input('pageSize') : 10;
        $sessionId = $request->session()->get('id');

        $userLogModel = TrafficUserLog::where(TrafficUserLog::FIELD_USER_ID, $sessionId)
            ->orderBy(TrafficServerLog::FIELD_LOG_AT, "DESC");

        $total = $userLogModel->count();
        $res = $userLogModel->forPage($reqCurrent, $reqPageSize)->get();

        return response([
            'data' => $res,
            'total' => $total
        ]);
    }


    /**
     * fetch traffic heatmap
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function trafficHeatMap(Request $request)
    {
        $reqStartAt = (int)$request->input('start_at') ?: strtotime('-365days 00:00:00');
        $sessionId = $request->session()->get('id');

        $userTrafficLogs = TrafficUserLog::select([
            TrafficUserLog::FIELD_LOG_DATE,
            TrafficUserLog::FIELD_LOG_AT,
            DB::raw('(u+d) as total')
        ])->where(TrafficUserLog::FIELD_USER_ID, $sessionId)->where(TrafficUserLog::FIELD_LOG_AT, '>=', $reqStartAt)->get();

        $data = [];
        /**
         * @var TrafficUserLog $log
         */
        foreach ($userTrafficLogs as $log) {
            $log->makeHidden([TrafficUserLog::FIELD_LOG_DATE]);
            $data[$log->getAttribute(TrafficUserLog::FIELD_LOG_DATE)] = $log;
        }

        return response([
            'data' => $data
        ]);
    }

}