<?php

namespace App\Providers;

use App\Services\Sms\ArraySender;
use App\Services\Sms\SmsRu;
use App\Services\Sms\SmsSender;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
