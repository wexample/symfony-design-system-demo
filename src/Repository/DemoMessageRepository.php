<?php

namespace Wexample\SymfonyDesignSystemDemo\Repository;

use Doctrine\ORM\QueryBuilder;
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

    /** The thread as it was said, oldest first, left open so it can be paginated. */
    public function queryByRoomOldestFirst(DemoRoom $room): QueryBuilder
    {
        return $this
            ->queryByField('room', $room)
            ->orderBy($this->getEntityQueryAlias() . '.dateCreated', self::SORT_ASC);
    }
}
