<?php

namespace Wexample\SymfonyDesignSystemDemo\Controller\Pages\DesignSystem\Generic;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyDesignSystemDemo\Controller\Pages\DesignSystem\AbstractDesignSystemGenericController;
use Wexample\SymfonyDesignSystemDemo\Service\DemoChatService;
use Wexample\SymfonyLoader\Controller\Pages\AbstractDesignSystemController;
use Wexample\SymfonyRouting\Attribute\TemplateBasedRoutes;

#[Route(
    name: 'wexample_design_system_generic_data_',
    path: AbstractDesignSystemController::CONTROLLER_BASE_ROUTE . '/generic/data/',
)]
#[TemplateBasedRoutes]
final class DataController extends AbstractDesignSystemGenericController
{
    /** The room the live chat of the page talks in, made on the first visit. */
    public const DEMO_ROOM_NAME = 'chat';

    /**
     * The one page of the section that is not template-only: a live chat needs
     * something to be live about, and that is a room the browser can subscribe
     * to before any message exists.
     */
    #[Route(path: 'chat', name: 'chat')]
    public function chat(DemoChatService $demoChatService): Response
    {
        return $this->renderPage('chat', [
            'demo_room' => $demoChatService->findOrCreateShowcaseRoom(self::DEMO_ROOM_NAME),
        ]);
    }
}
