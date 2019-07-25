<?php

return [
    /**
     * название класса-провайдера
     * Доступные провайдеры:
     * * \N1Creator\LaravelSmsNotifications\Providers\SmsRu (alias: smsru)
     * @see N1Creator\LaravelSmsNotifications\Providers
     */
    'provider' => 'smsru',
    /**
     * настройки, специфичные для провайдера
     */
    'provider_options' => [
        'api_id' => env('SMSRU_KEY'),
    ],
];
