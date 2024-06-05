<?php

namespace N1Creator\LaravelSmsNotifications\Zelenin;

abstract class AbstractSms
{

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $time;

    /**
     * @var string
     */
    public $translit;

    /**
     * @var string
     */
    public $test;

    /**
     * @var string
     */
    public $partner_id;

    /**
     * @var string
     */
    public $ip;
}
