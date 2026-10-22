<?php

namespace Wexample\SymfonyDesignSystemDemo\Traits;

use Wexample\SymfonyDesignSystemDemo\WexampleSymfonyDesignSystemDemoBundle;
use Wexample\SymfonyHelpers\Traits\BundleClassTrait;

trait SymfonyDesignSystemDemoBundleClassTrait
{
    use BundleClassTrait;

    public static function getBundleClassName(): string
    {
        return WexampleSymfonyDesignSystemDemoBundle::class;
    }
}
