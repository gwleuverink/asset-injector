<?php

namespace Leuverink\AssetInjector;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        Event::listen(
            RequestHandled::class,
            InjectAssets::class,
        );
    }
}
