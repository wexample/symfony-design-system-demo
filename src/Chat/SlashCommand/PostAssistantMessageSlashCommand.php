<?php

namespace Wexample\SymfonyDesignSystemDemo\Chat\SlashCommand;

use Wexample\SymfonyDesignSystem\Chat\SlashCommand\Attribute\AsSlashCommand;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoMessage;
use Wexample\SymfonyDesignSystemDemo\Service\DemoChatService;

#[AsSlashCommand(
    name: 'assistant',
    group: DemoChatService::SLASH_COMMAND_GROUP,
    description: 'Writes an answer as the model would have written it.',
    usage: '/assistant [text]',
)]
class PostAssistantMessageSlashCommand extends AbstractPostMessageSlashCommand
{
    protected function getMessageType(): string
    {
        return DemoMessage::TYPE_ASSISTANT;
    }

    protected function getDefaultBody(): string
    {
        return 'An answer, as the model would have written it.';
    }
}
