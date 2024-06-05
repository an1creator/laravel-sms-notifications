<?php

namespace N1Creator\LaravelSmsNotifications\Providers;

use N1Creator\LaravelSmsNotifications\Contracts\Provider;
use N1Creator\LaravelSmsNotifications\Sms\Api;
use N1Creator\LaravelSmsNotifications\Sms\ApiIdAuth;
use N1Creator\LaravelSmsNotifications\Sms\Sms;
use N1Creator\SmsRu as SmsRuApi;

class SmsRu implements Provider
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
        $response = $this->getClient()->smsSend(
            $this->applyOptions(new Sms($phone, $text), $options)
        );

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
        $smsList = array_map(function ($phone) use ($message, $options) {
            return new $this->applyOptions(new Sms($phone, $message), $options);
        }, $phones);
        $response = $this->getClient()->smsSend(new SmsRuApi\Entity\SmsPool($smsList));

        return $this->checkResponse($response);
    }

    /**
     * @return SmsRuApi\Api
     */
    private function getClient()
    {
        if (!$this->client) {
            return $this->client = new Api(
                new ApiIdAuth(
                    $this->options['api_id']
                ),
                new SmsRuApi\Client\Client()
            );
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

    private function applyOptions($object, $options)
    {
        foreach ($options as $option => $value) {
            $object->$option = $value;
        }

        return $object;
    }
}
