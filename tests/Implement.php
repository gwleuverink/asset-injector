<?php

namespace Tests;

use Leuverink\InjectAssets\Contracts\AssetInjector;

class Implement implements AssetInjector
{
    public function identifier(): string
    {
        return 'TEST_PACKAGE';
    }

    public function enabled(): bool
    {
        return true;
    }

    public function inject(): string
    {
        return 'TEST_PACKAGE_ASSETS_INJECTED';
    }
}
