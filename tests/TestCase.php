<?php

namespace Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;

static $latestResponse = null;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;
}
