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
            new SmsRuApi\Entity\Sms($phone, $text)
        );

        return $response->code == self::CODE_OK;
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
        $smsList = array_map(function ($phone) use ($message) {
            return new SmsRuApi\Entity\Sms($phone, $message);
        }, $phones);
        $response = $this->getClient()->smsSend(new SmsRuApi\Entity\SmsPool($smsList));

        return $response->code == self::CODE_OK;
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
}
