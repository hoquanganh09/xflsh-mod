<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;


class TelegramController extends Controller
{
    public function getBotInfo()
    {
        $token = config('v2board.telegram_bot_token');

        try {
            $telegramAPI = new Api($token, false);
            $result = $telegramAPI->getMe();
        } catch (TelegramSDKException $e) {
            abort(500, $e->getMessage());
        }

        return response([
            'data' => [
                'username' => $result->username
            ]
        ]);
    }
}