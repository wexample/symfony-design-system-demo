<?php

namespace Wexample\SymfonyDesignSystemDemo\Chat\SlashCommand;

use Wexample\SymfonyDesignSystem\Chat\SlashCommand\Attribute\AsSlashCommand;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoMessage;
use Wexample\SymfonyDesignSystemDemo\Service\DemoChatService;

#[AsSlashCommand(
    name: 'error',
    group: DemoChatService::SLASH_COMMAND_GROUP,
    description: 'Writes a failure, as the thread shows one.',
    usage: '/error [text]',
)]
class PostErrorMessageSlashCommand extends AbstractPostMessageSlashCommand
{
    protected function getMessageType(): string
    {
        return DemoMessage::TYPE_ERROR;
    }

    protected function getDefaultBody(): string
    {
        return 'The model did not answer: the request timed out.';
    }
}
