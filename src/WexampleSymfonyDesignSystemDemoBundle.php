<?php

namespace Wexample\SymfonyDesignSystemDemo;

use Wexample\SymfonyDesignSystem\Interface\DesignSystemElementsBundleInterface;
use Wexample\SymfonyHelpers\Class\AbstractBundle;
use Wexample\SymfonyHelpers\Helper\BundleHelper;
use Wexample\SymfonyHelpers\Interface\LoaderBundleInterface;
use Wexample\SymfonyPseudocode\Interface\PseudocodeBundleInterface;

class WexampleSymfonyDesignSystemDemoBundle extends AbstractBundle implements LoaderBundleInterface, PseudocodeBundleInterface, DesignSystemElementsBundleInterface
{
    public static function getLoaderFrontPaths(): array
    {
        return [
            BundleHelper::getBundleCssAlias(static::class) => __DIR__ . '/../assets/',
        ];
    }

    public static function getDesignSystemElementsPath(): string
    {
        return __DIR__ . '/../assets/';
    }

    public static function getPseudocodeSourcePaths(): array
    {
        return [__DIR__.'/'];
    }
}
