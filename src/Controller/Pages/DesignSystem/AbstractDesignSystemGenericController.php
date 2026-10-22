<?php

namespace Wexample\SymfonyDesignSystemDemo\Controller\Pages\DesignSystem;

use Wexample\SymfonyDesignSystemDemo\Traits\SymfonyDesignSystemDemoBundleClassTrait;
use Wexample\SymfonyLoader\Controller\AbstractPagesController;

abstract class AbstractDesignSystemGenericController extends AbstractPagesController
{
    use SymfonyDesignSystemDemoBundleClassTrait;
}
