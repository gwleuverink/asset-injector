<?php

namespace Leuverink\AssetInjector\Contracts;

interface AssetInjector
{
    public function identifier(): string;

    public function enabled(): bool;

    public function inject(): string;
}
