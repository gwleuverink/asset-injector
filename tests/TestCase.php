<?php

namespace Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Leuverink\AssetInjector\Contracts\AssetInjector;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            AssetInjector::class,
            Implement::class
        );
    }
}
