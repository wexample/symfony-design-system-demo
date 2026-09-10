<?php

namespace Wexample\SymfonyDesignSystemDemo\Repository;

use Wexample\SymfonyDesignSystemDemo\Entity\DemoRoom;
use Wexample\SymfonyDesignSystemDemo\Entity\Traits\Manipulator\DemoRoomEntityManipulatorTrait;
use Wexample\SymfonyHelpers\Repository\AbstractRepository;

/**
 * @method DemoRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoRoom[]    findAll()
 * @method DemoRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoRoomRepository extends AbstractRepository
{
    use DemoRoomEntityManipulatorTrait;

    public function findOneByName(string $name): ?DemoRoom
    {
        return $this->findOneBy(['name' => $name]);
    }
}
