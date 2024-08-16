<?php

namespace Leuverink\InjectAssets\Contracts;

interface AssetInjector
{
    public function identifier(): string;

    public function inject(): string;
}
