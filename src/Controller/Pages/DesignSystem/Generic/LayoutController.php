<?php

namespace Wexample\SymfonyDesignSystemDemo\Controller\Pages\DesignSystem\Generic;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyDesignSystemDemo\Controller\Pages\DesignSystem\AbstractDesignSystemGenericController;
use Wexample\SymfonyHelpers\Controller\AbstractController;
use Wexample\SymfonyLoader\Controller\Pages\AbstractDesignSystemController;
use Wexample\SymfonyRouting\Attribute\TemplateBasedRoutes;

#[Route(
    name: 'wexample_design_system_generic_layout_',
    path: AbstractDesignSystemController::CONTROLLER_BASE_ROUTE . '/generic/layout/',
)]
#[TemplateBasedRoutes]
final class LayoutController extends AbstractDesignSystemGenericController
{
    final public const ROUTE_DASHBOARD_PANEL = 'dashboard_panel';

    // The longest a panel may be asked to keep the visitor waiting.
    private const DASHBOARD_PANEL_DELAY_MAX = 5;

    /**
     * A panel of the dashboard demo, loaded into one of its embeds. Asked with
     * `delay`, it answers that many seconds late on purpose: the spinner an
     * embed shows while a slow page is on its way is what the demo is for.
     */
    #[Route(path: 'dashboard-panel', name: self::ROUTE_DASHBOARD_PANEL, options: AbstractController::ROUTE_OPTIONS_ONLY_EXPOSE)]
    public function dashboardPanel(Request $request): Response
    {
        $delay = min(self::DASHBOARD_PANEL_DELAY_MAX, max(0, (float) $request->query->get('delay', 0)));

        if ($delay > 0) {
            usleep((int) ($delay * 1_000_000));
        }

        return $this->renderPage(self::ROUTE_DASHBOARD_PANEL, [
            'service' => (string) $request->query->get('service', 'api'),
            'delay' => $delay,
        ]);
    }
}
