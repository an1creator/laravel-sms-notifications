<?php

namespace N1Creator\LaravelSmsNotifications\Notifications;

use Illuminate\Notifications\Notification;
use N1Creator\LaravelSmsNotifications\Providers\SmsKz;
use N1Creator\LaravelSmsNotifications\SmsSender;

class KzSmsChannel
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
    public function __construct()
    {
        $this->sender = new SmsSender($this->getProvider());
    }

    private function getProvider()
    {
        return new SmsKz(config('sms.provider_options.smskz'));
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
