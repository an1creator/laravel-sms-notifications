<?php

namespace N1Creator\LaravelSmsNotifications\Contracts;

interface Provider
{
    /**
     * @param $phone
     * @param $message
     * @param array $options
     * @return bool
     */
    public function send($phone, $message, array $options = []): bool;

    /**
     * @param array $phones
     * @param $message
     * @param array $options
     * @return bool
     */
    public function sendBatch(array $phones, $message, array $options = []): bool;
}
