<?php

namespace Wexample\SymfonyDesignSystemDemo\Entity\Traits\Manipulator;

use Wexample\SymfonyDesignSystemDemo\Entity\DemoMessage;
use Wexample\SymfonyHelpers\Entity\Traits\Manipulator\EntityManipulatorTrait;

trait DemoMessageEntityManipulatorTrait
{
    use EntityManipulatorTrait;

    public static function getEntityClassName(): string
    {
        return DemoMessage::class;
    }
}
