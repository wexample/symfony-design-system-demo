<?php

namespace Wexample\SymfonyDesignSystemDemo\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Wexample\SymfonyDesignSystemDemo\Service\DemoChatService;

/**
 * The answer is written after the response has left, which is what makes it
 * arrive by the hub and never by the return of the POST.
 */
#[AsEventListener(event: KernelEvents::TERMINATE)]
class DemoChatReplyListener
{
    public function __construct(
        private readonly DemoChatService $demoChatService,
    ) {
    }

    public function __invoke(TerminateEvent $event): void
    {
        $this->demoChatService->postScheduledReplies();
    }
}
