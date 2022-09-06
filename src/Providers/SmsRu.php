<?php

namespace N1Creator\LaravelSmsNotifications\Providers;

use N1Creator\LaravelSmsNotifications\Contracts\Provider;
use Zelenin\SmsRu as SmsRuApi;

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
            $this->applyOptions(new SmsRuApi\Entity\Sms($phone, $text), $options)
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
            return new $this->applyOptions(new SmsRuApi\Entity\Sms($phone, $message), $options);
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
            return $this->client = new SmsRuApi\Api(
                new SmsRuApi\Auth\ApiIdAuth(
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
