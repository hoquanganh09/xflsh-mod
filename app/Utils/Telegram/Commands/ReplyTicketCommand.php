<?php

namespace App\Utils\Telegram\Commands;

use App\Jobs\SendTelegramJob;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Services\NoticeService;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Commands\Command;

class ReplyTicketCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "replyTicket";

    /**
     * @var string Command Description
     */
    protected $description = "Trả lời hỗ trợ người dùng";


    /**
     * @inheritdoc
     */
    public function handle()
    {
        preg_match("/[#](.*)/", $this->getUpdate()->getMessage()->replyToMessage->text, $match);
        $ticketId = $match[1] ?? 0;
        $ticketId = (int)$ticketId;

        if ($ticketId <= 0) {
            return;
        }

        $chatId = $this->getUpdate()->getChat()->id;
        $msgText = $this->getUpdate()->getMessage()->text;

        /**
         * @var User $user
         */
        $user = User::where(User::FIELD_TELEGRAM_ID, $chatId)->first();
        if ($user === null) {
            $this->replyWithMessage(["text" => 'Người dùng không tồn tại']);
            return;
        }

        if (!$user->isAdmin() && !$user->isStaff()) {
            return;
        }

        /**
         * @var Ticket $ticket
         */
        $ticket = Ticket::find($ticketId);
        if ($ticket == null) {
            $this->replyWithMessage(['text' => 'Phiếu hỗ trợ không tồn tại']);
            return;
        }

        if ($ticket->isClosed()) {
            $this->replyWithMessage(['text' => 'Phiếu hỗ trợ đã đóng và không thể trả lời']);
            return;
        }

        DB::beginTransaction();
        $ticketMessage = new TicketMessage();
        $ticketMessage->setAttribute(TicketMessage::FIELD_USER_ID, $user->getKey());
        $ticketMessage->setAttribute(TicketMessage::FIELD_TICKET_ID, $ticket->getKey());
        $ticketMessage->setAttribute(TicketMessage::FIELD_MESSAGE, $msgText);
        $ticket->setAttribute(Ticket::FIELD_LAST_REPLY_USER_ID, $user->getKey());

        if (!$ticketMessage->save() || !$ticket->save()) {
            DB::rollback();
            $this->replyWithMessage(['text' => 'Trả lời hỗ trợ không thành công']);
            return;
        }
        DB::commit();
        NoticeService::sendEmailNotify($ticket, $ticketMessage);


        if (!config('v2board.telegram_bot_enable', 0)) {
            return;
        }
        $this->replyWithMessage([
            'text' => "#`$ticketId` Phiếu hỗ trợ đã được trả lời thành công",
        ]);

        SendTelegramJob::generateJobWithAdminMessages("#`$ticketId` Phiếu yêu cầu hỗ trợ được tạo bởi $user->email hãy phản hồi", true);
    }
}