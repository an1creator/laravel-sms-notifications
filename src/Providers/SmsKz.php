<?php

namespace N1Creator\LaravelSmsNotifications\Providers;

use N1Creator\LaravelSmsNotifications\Contracts\Provider;
use N1Creator\LaravelSmsNotifications\Api\SmsKzApi;

class SmsKz implements Provider
{
    const CODE_OK = 100;

    /**
     * @var SmsRuApi\Api
     */
    private $client;

    /**
     * @var array
     */
    private $options;

    /**
     * SmsRuDriver constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param $phone
     * @param $text
     * @param array $options
     *
     * @throws SmsRuApi\Exception\Exception
     *
     * @return bool
     */
    public function send($phone, $text, array $options = []): bool
    {
        $options['phones'] = '+7' . $phone;
        $options['mes'] = $text;

        $response = $this->getClient()->smsSend($options);

        return $this->checkResponse($response);
    }

    /**
     * @param array $phones
     * @param $message
     * @param array $options
     *
     * @throws SmsRuApi\Exception\Exception
     *
     * @return bool
     */
    public function sendBatch(array $phones, $message, array $options = []): bool
    {
        $options['phones'] = implode(',+7', $phones);
        $options['mes'] = $message;

        $response = $this->getClient()->smsSend($options);

        return $this->checkResponse($response);
    }

    /**
     * @return SmsRuApi\Api
     */
    private function getClient(): SmsKzApi
    {
        if (!$this->client) {
            return $this->client = new SmsKzApi(config('sms.provider_options.smskz'));
        }

        return $this->client;
    }

    private function checkResponse($response)
    {
        if ($response->code != self::CODE_OK) {
            throw new SmsRuApi\Exception\Exception($response->getDescription(), $response->code);
        }

        return true;
    }
}
