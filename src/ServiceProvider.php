<?php

namespace N1Creator\LaravelSmsNotifications;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var array
     */
    private $providerAliases = [
        'smsru' => Providers\SmsRu::class,
        'smskz' => Providers\SmsKz::class,
    ];

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('sms.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton(SmsSender::class, function ($app) {
            $providerClass = config('sms.provider');
            if (array_key_exists($providerClass, $this->providerAliases)) {
                $providerClass = $this->providerAliases[$providerClass];
            }

            return new SmsSender(
                $app->make($providerClass, [
                    'options' => config('sms.provider_options')[config('sms.provider')],
                ])
            );
        });
    }
}
