<?php

namespace N1Creator\LaravelSmsNotifications\Notifications;

use Illuminate\Notifications\Notification;
use N1Creator\LaravelSmsNotifications\SmsSender;

class SmsChannel
{
    /**
     * @var SmsSender
     */
    private $sender;

    /**
     * constructor.
     *
     * @param SmsSender $sender
     */
    public function __construct(SmsSender $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $phone = $notifiable->routeNotificationFor('sms');

        if (!$phone) {
            return;
        }

        /** @var SmsMessage $message */
        $message = $notification->toSms($notifiable);

        if (!($message instanceof SmsMessage)) {
            $message = new SmsMessage($message);
        }

        if (is_array($phone)) {
            foreach ($phone as $value) {
                $this->sender->send($value, $message->getContent(), $message->getOptions());
            }
        } else {
            $this->sender->send($phone, $message->getContent(), $message->getOptions());
        }
    }
}
