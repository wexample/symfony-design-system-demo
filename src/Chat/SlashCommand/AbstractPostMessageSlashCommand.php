<?php

namespace Wexample\SymfonyDesignSystemDemo\Chat\SlashCommand;

use Wexample\SymfonyDesignSystem\Chat\SlashCommand\SlashCommandContext;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoRoom;
use Wexample\SymfonyDesignSystemDemo\Service\DemoChatService;

/**
 * Writes into the thread a kind of line the composer cannot produce by typing.
 *
 * The demo has no model and no tools: these lines exist so the page can show
 * what each of them looks like, which is the whole reason the chat offers them
 * as commands.
 */
abstract class AbstractPostMessageSlashCommand
{
    public function __construct(
        protected readonly DemoChatService $demoChatService,
    ) {
    }

    abstract protected function getMessageType(): string;

    /**
     * What is written when the command is typed alone.
     */
    abstract protected function getDefaultBody(): string;

    public function __invoke(SlashCommandContext $context): void
    {
        /** @var DemoRoom $room */
        $room = $context->get(DemoChatService::SLASH_COMMAND_PARAM_ROOM);

        $this->demoChatService->postMessage(
            $room,
            $this->getMessageType(),
            $context->text ?: $this->getDefaultBody()
        );
    }
}
