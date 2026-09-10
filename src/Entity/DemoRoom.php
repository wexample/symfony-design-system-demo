<?php

namespace Wexample\SymfonyDesignSystemDemo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Wexample\Pseudocode\Attribute\PseudocodeExport;
use Wexample\SymfonyApi\Attribute\ApiEntity;
use Wexample\SymfonyDesignSystemDemo\Repository\DemoRoomRepository;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;
use Wexample\SymfonyHelpers\Entity\Traits\HasNameTrait;

/**
 * What the demos hold their messages in, and the thing a browser subscribes to.
 *
 * A chat watches one room and not each of its lines: the room is what outlives
 * the messages and is known before any of them exists, so it is the only name a
 * subscription can be opened on before the first arrival.
 *
 * Its identity derives from its name, so the seed can run again and again and
 * still leave one room.
 */
#[ApiEntity]
#[PseudocodeExport(inherited: true)]
#[ORM\Entity(repositoryClass: DemoRoomRepository::class)]
#[ORM\Table(name: 'demo_room')]
#[ORM\UniqueConstraint(columns: ['name'])]
class DemoRoom extends AbstractEntity
{
    use HasNameTrait;

    public const ID_NAMESPACE = '5b2e7c14-93a6-5d48-8f10-6a4c2e9b7d35';

    public function __construct(string $name)
    {
        parent::__construct();

        $this->name = $name;

        $this->setId(self::idFor($name));
    }

    public static function idFor(string $name): Uuid
    {
        return Uuid::v5(Uuid::fromString(self::ID_NAMESPACE), $name);
    }
}
