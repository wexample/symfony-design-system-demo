<?php

namespace Wexample\SymfonyDesignSystemDemo\Chat\SlashCommand;

use Wexample\SymfonyDesignSystem\Chat\SlashCommand\Attribute\AsSlashCommand;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoMessage;
use Wexample\SymfonyDesignSystemDemo\Service\DemoChatService;

#[AsSlashCommand(
    name: 'system',
    group: DemoChatService::SLASH_COMMAND_GROUP,
    description: 'Writes a notice from the machine, not from anyone speaking.',
    usage: '/system [text]',
)]
class PostSystemMessageSlashCommand extends AbstractPostMessageSlashCommand
{
    protected function getMessageType(): string
    {
        return DemoMessage::TYPE_SYSTEM;
    }

    protected function getDefaultBody(): string
    {
        return 'A notice from the machine, not from anyone speaking.';
    }
}
