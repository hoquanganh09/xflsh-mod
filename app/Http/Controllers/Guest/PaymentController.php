<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Jobs\SendTelegramJob;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * @throws Exception
     */
    public function notify($method, $uuid, Request $request)
    {
        try {

            $payment = Payment::findByUUID($uuid);
            if ($payment === null) {
                throw new Exception("payment not found");
            }

            $paymentService = new PaymentService($method, $payment);
            $verify = $paymentService->notify($request->input());
            if (!$verify) {
                throw new Exception("verify error");
            }

            $tradeNo = $verify['trade_no'];
            $callbackNo = $verify['callback_no'];
            /**
             * @var Order $order
             */
            $order = Order::findByTradeNo($tradeNo);
            if ($order === null) {
                throw new Exception("order not found");
            }

            /**
             * @var User $user
             */
            $user = $order->user();
            if ($user === null) {
                throw new Exception("user not found");
            }


            if ($order->getAttribute(Order::FIELD_STATUS) !== Order::STATUS_UNPAID) {
                Log::error("invalid order status", ['order' => $order->toArray(), "verify" => $verify]);
                throw new Exception("invalid order status");
            }

            $order->setAttribute(Order::FIELD_PAID_AT, time());
            $order->setAttribute(Order::FIELD_STATUS, Order::STATUS_PENDING);
            $order->setAttribute(Order::FIELD_CALLBACK_NO, $callbackNo);

            if (!$order->save()) {
                throw new Exception("order save failed");
            }

            $this->_notifyAdmin($order, $user);
            $this->_notifyUser($order, $user);
        } catch (Exception $e) {
            Log::error($e);
            abort(500, 'fail: ' . $e->getMessage());
        }

        die($paymentService->customResult ?? 'success');
    }

    /**
     * 通知管理员
     *
     * @param Order $order
     * @param User $user
     *
     * @return void
     */
    private function _notifyAdmin(Order $order, User $user): void
    {
        //通知
        $message = sprintf(
            "💰Thanh toán thành công %s đồng\n———————————————\nMã đơn hàng： %s\n———————————————\nEmail khách hàng: %s",
            $order->getAttribute(Order::FIELD_TOTAL_AMOUNT) / 100,
            $order->getAttribute(Order::FIELD_TRADE_NO),
            $user->getAttribute(User::FIELD_EMAIL)
        );
        SendTelegramJob::generateJobWithAdminMessages($message);
    }

    /**
     * 通知用户
     *
     * @param Order $order
     * @param User $user
     *
     * @return void
     */
    private function _notifyUser(Order $order, User $user): void
    {
        $content = sprintf(
            "✨Cảm ơn bạn đã thanh toán %s đồng, đơn hàng sẽ được kích hoạt từ 1-3 phút, Mã đơn hàng: %s",
            $order->getAttribute(Order::FIELD_TOTAL_AMOUNT) / 100,
            $order->getAttribute(Order::FIELD_TRADE_NO)
        );
        $subject = config('v2board.app_name', 'V2Board') . "Thanh toán thành công";
        SendEmailJob::dispatch([
            'email' => $user->getAttribute(User::FIELD_EMAIL),
            'subject' => $subject,
            'template_name' => 'notify',
            'template_value' => [
                'name' => config('v2board.app_name', 'V2Board'),
                'url' => config('v2board.app_url'),
                'content' => $content
            ]
        ]);

        $telegramId = (int)$user->getAttribute(User::FIELD_TELEGRAM_ID);
        if ($telegramId === 0) {
            return;
        }
        $message = sprintf(
            "✨Cảm ơn bạn đã thanh toán %s đồng，đơn hàng sẽ được kích hoạt từ 1-3 phút.\n———————————————\nMã đơn hàng：%s",
            $order->getAttribute(Order::FIELD_TOTAL_AMOUNT) / 100,
            $order->getAttribute(Order::FIELD_TRADE_NO)
        );
        SendTelegramJob::dispatch($telegramId, $message);
    }
}