<?php

namespace N1Creator\LaravelSmsNotifications\Sms;

class SmsPool extends AbstractSms
{

    /**
     * @var Sms[]
     */
    public $messages;

    /**
     * @param Sms[] $messages
     */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }
}
