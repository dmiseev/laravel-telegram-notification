<?php
declare(strict_types=1);

namespace Dmiseev\TelegramNotification;

use Illuminate\Notifications\Notification;

class TelegramChannel
{
    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @param Telegram $telegram
     */
    public function __construct(Telegram $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return void
     * @throws TelegramException
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toTelegram($notifiable);

        if (is_string($message)) {
            $message = TelegramMessage::create($message);
        }

        if ($message->toNotGiven()) {

            if (!$to = $notifiable->routeNotificationFor('telegram')) {
                throw TelegramException::chatIdNotProvided();
            }

            $message->to($to);
        }

        $params = $message->toArray();
        $this->telegram->sendMessage($params);
    }
}