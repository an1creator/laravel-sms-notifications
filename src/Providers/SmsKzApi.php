<?php

namespace N1Creator\LaravelSmsNotifications\Providers;

use Exception;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Arr;

class SmsKzApi
{
    /** @var HttpClient */
    protected $client;

    /** @var string */
    protected $endpoint;

    /** @var string */
    protected $login;

    /** @var string */
    protected $secret;

    /** @var string */
    protected $sender;

    /** @var array */
    protected $extra;

    public function __construct(array $config)
    {
        $this->login = Arr::get($config, 'login');
        $this->secret = Arr::get($config, 'secret');
        $this->endpoint = Arr::get($config, 'host', 'https://smsc.kz/') . 'rest/send/';

        $this->client = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
        ]);
    }

    public function smsSend($params)
    {
        $base = [
            'login'   => $this->login,
            'psw'     => $this->secret,
        ];

        $params = \array_merge($base, \array_filter($params));

        try {
            $response = $this->client->request('POST', $this->endpoint, ['json' => $params]);

            $response = \json_decode((string) $response->getBody(), true);

            if (isset($response['error'])) {
                throw new \DomainException($response['error'], $response['error_code']);
            }

            return $response;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
