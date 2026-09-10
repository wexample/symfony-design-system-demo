<?php

namespace Wexample\SymfonyDesignSystemDemo\Repository;

use Wexample\SymfonyDesignSystemDemo\Entity\DemoRoom;
use Wexample\SymfonyDesignSystemDemo\Entity\Traits\Manipulator\DemoRoomEntityManipulatorTrait;
use Wexample\SymfonyHelpers\Repository\AbstractRepository;

/**
 * @method DemoRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoRoom      saveNewDemoRoom(string $name)
 * @method DemoRoom[]    findAll()
 * @method DemoRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoRoomRepository extends AbstractRepository
{
    use DemoRoomEntityManipulatorTrait;

    public function createNewDemoRoom(string $name): DemoRoom
    {
        return new DemoRoom($name);
    }

    /**
     * A demo has no fixtures: the room a page needs is made the first time that
     * page is opened, and found on every visit after.
     */
    public function findOrCreateOneByName(string $name): DemoRoom
    {
        return $this->findOneByName($name)
            ?? $this->saveNewDemoRoom($name);
    }

    public function findOneByName(string $name): ?DemoRoom
    {
        return $this->findOneBy(['name' => $name]);
    }
}
