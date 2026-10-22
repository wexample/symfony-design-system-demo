<?php

namespace Wexample\SymfonyDesignSystemDemo\Controller\Pages\DesignSystem\Generic;

use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyDesignSystemDemo\Controller\Pages\DesignSystem\AbstractDesignSystemGenericController;
use Wexample\SymfonyLoader\Controller\Pages\AbstractDesignSystemController;
use Wexample\SymfonyRouting\Attribute\TemplateBasedRoutes;

/**
 * What everything else is built out of: the tokens themselves.
 *
 * Nothing here is a component. These pages show the scales — colour, spacing,
 * type — that every other section spends without ever showing them.
 */
#[Route(
    name: 'wexample_design_system_generic_foundations_',
    path: AbstractDesignSystemController::CONTROLLER_BASE_ROUTE . '/generic/foundations/',
)]
#[TemplateBasedRoutes]
final class FoundationsController extends AbstractDesignSystemGenericController
{
}
