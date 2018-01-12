<?php
declare(strict_types=1);

namespace Dmiseev\TelegramNotification;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class TelegramNotificationProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
//        $this->app->when(TelegramChannel::class)
//            ->needs(Telegram::class)
//            ->give(function () {
//                return new Telegram(
//                    config('services.telegram.token'),
//                    new HttpClient
//                );
//            });

        $this->app->singleton(TelegramChannel::class, function () {
            return new Telegram(
                new HttpClient,
                config('services.telegram.token')
            );
        });
    }

    /**
     * Register any package services.
     */
    public function register()
    {
    }
}