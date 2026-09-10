<?php

namespace Wexample\SymfonyDesignSystemDemo\Chat\SlashCommand;

use Wexample\SymfonyDesignSystem\Chat\SlashCommand\Attribute\AsSlashCommand;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoMessage;
use Wexample\SymfonyDesignSystemDemo\Service\DemoChatService;

#[AsSlashCommand(
    name: 'tool',
    group: DemoChatService::SLASH_COMMAND_GROUP,
    description: 'Writes a tool call and what it returned.',
    usage: '/tool [call]',
)]
class PostToolMessageSlashCommand extends AbstractPostMessageSlashCommand
{
    protected function getMessageType(): string
    {
        return DemoMessage::TYPE_TOOL;
    }

    protected function getDefaultBody(): string
    {
        return 'demo.run(argument: "value") → done';
    }
}
