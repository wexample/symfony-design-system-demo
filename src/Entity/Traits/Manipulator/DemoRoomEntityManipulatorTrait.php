<?php

namespace Wexample\SymfonyDesignSystemDemo\Entity\Traits\Manipulator;

use Wexample\SymfonyDesignSystemDemo\Entity\DemoRoom;
use Wexample\SymfonyHelpers\Entity\Traits\Manipulator\EntityManipulatorTrait;

trait DemoRoomEntityManipulatorTrait
{
    use EntityManipulatorTrait;

    public static function getEntityClassName(): string
    {
        return DemoRoom::class;
    }
}
