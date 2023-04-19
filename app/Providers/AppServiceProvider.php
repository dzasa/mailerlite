<?php

namespace App\Providers;

use App\Services\MailerLite\MailerLite;
use App\Services\MailerLite\MailerLiteInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MailerLiteInterface::class, MailerLite::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
