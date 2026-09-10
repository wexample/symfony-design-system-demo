<?php

namespace Wexample\SymfonyDesignSystemDemo\Api\Normalizer\Entity\DemoMessage;

use ArrayObject;
use Wexample\SymfonyDesignSystemDemo\Api\Dto\PublicDemoMessageDto;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoMessage;
use Wexample\SymfonyDesignSystemDemo\Entity\Traits\Manipulator\DemoMessageEntityManipulatorTrait;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;
use Wexample\SymfonyHelpers\Interface\NormalizableDataInterface;
use Wexample\SymfonyHelpers\Normalizer\AbstractEntityNormalizer;

class DefaultDemoMessageNormalizer extends AbstractEntityNormalizer
{
    use DemoMessageEntityManipulatorTrait;

    protected function normalizeEntity(
        DemoMessage|AbstractEntity $entity,
        ?string $format = null,
        array $context = []
    ): array|string|int|float|bool|ArrayObject|NormalizableDataInterface|null {
        return PublicDemoMessageDto::fromEntity($entity);
    }
}
