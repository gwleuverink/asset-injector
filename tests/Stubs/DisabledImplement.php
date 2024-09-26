<?php

namespace Tests\Stubs;

use Leuverink\AssetInjector\Contracts\AssetInjector;

class DisabledImplement implements AssetInjector
{
    public function identifier(): string
    {
        return 'DISABLED_TEST_PACKAGE';
    }

    public function enabled(): bool
    {
        return false;
    }

    public function inject(): string
    {
        return 'DISABLED_TEST_PACKAGE_ASSETS_INJECTED';
    }
}
