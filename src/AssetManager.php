<?php

namespace Leuverink\AssetInjector;

use Illuminate\Support\Facades\Event;
use Leuverink\AssetInjector\Contracts\AssetInjector;
use Illuminate\Foundation\Http\Events\RequestHandled;

class AssetManager
{
    public static function register(AssetInjector $injector)
    {
        Event::listen(function (RequestHandled $event) use ($injector) {
            $listener = new InjectAssets($injector);
            $listener($event);
        });
    }
}
