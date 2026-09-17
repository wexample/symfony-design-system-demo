<?php

namespace Wexample\SymfonyDesignSystemDemo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Wexample\Pseudocode\Attribute\PseudocodeExport;
use Wexample\SymfonyApi\Attribute\ApiEntity;
use Wexample\SymfonyDesignSystemDemo\Repository\DemoMessageRepository;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;
use Wexample\SymfonyHelpers\Entity\Traits\HasBodyTrait;
use Wexample\SymfonyHelpers\Entity\Traits\HasDateCreatedTrait;
use Wexample\SymfonyHelpers\Entity\Traits\HasTypeTrait;
use Wexample\SymfonySearch\Attribute\Searchable;

/**
 * One line said in a room, kept only so the demos have something real to show.
 *
 * Who spoke is its type and nothing else: a demo has no accounts, and the name
 * the reader sees is a translation of that type rather than a stored label.
 */
#[ApiEntity]
#[Searchable]
#[PseudocodeExport(inherited: true)]
#[ORM\Entity(repositoryClass: DemoMessageRepository::class)]
#[ORM\Table(name: 'demo_message')]
class DemoMessage extends AbstractEntity
{
    use HasBodyTrait;
    use HasDateCreatedTrait;
    use HasTypeTrait;

    public const TYPE_ASSISTANT = 'assistant';
    public const TYPE_ERROR = 'error';
    public const TYPE_SYSTEM = 'system';
    public const TYPE_TOOL = 'tool';
    public const TYPE_USER = 'user';

    #[ORM\ManyToOne(targetEntity: DemoRoom::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    protected DemoRoom $room;

    public function __construct(DemoRoom $room)
    {
        parent::__construct();

        $this->room = $room;

        $this->setDateCreatedNow();
    }

    /** @return string[] */
    public static function getAllowedTypes(): ?array
    {
        return [
            self::TYPE_ASSISTANT,
            self::TYPE_ERROR,
            self::TYPE_SYSTEM,
            self::TYPE_TOOL,
            self::TYPE_USER,
        ];
    }

    public function getRoom(): DemoRoom
    {
        return $this->room;
    }
}
