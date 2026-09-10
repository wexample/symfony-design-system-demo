<?php

namespace Wexample\SymfonyDesignSystemDemo\Repository;

use Wexample\SymfonyDesignSystemDemo\Entity\DemoMessage;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoRoom;
use Wexample\SymfonyDesignSystemDemo\Entity\Traits\Manipulator\DemoMessageEntityManipulatorTrait;
use Wexample\SymfonyHelpers\Repository\AbstractRepository;

/**
 * @method DemoMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoMessage[]    findAll()
 * @method DemoMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoMessageRepository extends AbstractRepository
{
    use DemoMessageEntityManipulatorTrait;

    /**
     * The thread as it was said, oldest first.
     *
     * @return DemoMessage[]
     */
    public function findByRoom(DemoRoom $room): array
    {
        return $this->findBy(
            ['room' => $room],
            ['dateCreated' => self::SORT_ASC]
        );
    }
}
