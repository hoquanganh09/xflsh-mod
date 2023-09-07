<?php

namespace App\Utils\Telegram\Commands;

use App\Models\User;
use Telegram\Bot\Commands\Command;

class BindCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "bind";

    /**
     * @var string Command Description
     */
    protected $description = "Liên kết tài khoản telegram";

    /**
     * @var string   Command Argument Pattern
     */
    protected $pattern = '.*+';

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $subscribeURL = $this->arguments['custom'] ?? '';

        if (empty($subscribeURL)) {
            $this->replyWithMessage(['text' => 'Thông số sai, vui lòng gửi kèm theo link liên kết tài khoản']);
            return;
        }

        $url = parse_url($subscribeURL);
        if (empty($url['query'])) {
            $this->replyWithMessage(['text' => 'Địa chỉ đăng ký không hợp lệ']);
            return;
        }

        parse_str($url['query'], $query);
        $token = $query['token'] ?? null;
        if (!$token) {
            $this->replyWithMessage(['text' => 'Link liên kết không hợp lệ']);
            return;
        }

        /**
         * @var User $user
         */
        $user = User::findByToken($token);
        if ($user === null) {
            $this->replyWithMessage(['text' => 'Người dùng không tồn tại']);
            return;
        }

        if ($user->getAttribute(User::FIELD_TELEGRAM_ID)) {
            $this->replyWithMessage(['text' => 'Liên kết tài khoản thành công']);
            return;
        }

        $user->setAttribute(User::FIELD_TELEGRAM_ID, $this->getUpdate()->getChat()->id);
        if (!$user->save()) {
            $this->replyWithMessage(['text' => 'Thiết lập không thành công']);
            return;
        }

        $this->replyWithMessage(['text' => 'Liên kết thành công']);
    }
}