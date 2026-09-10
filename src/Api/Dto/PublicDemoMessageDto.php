<?php

namespace Wexample\SymfonyDesignSystemDemo\Api\Dto;

use DateTimeInterface;
use Wexample\SymfonyApi\Api\Dto\AbstractEntityDto;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoMessage;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;

class PublicDemoMessageDto extends AbstractEntityDto
{
    public ?string $body;

    public ?string $dateCreated;

    public string $room;

    public ?string $type;

    /**
     * @param DemoMessage $entity
     */
    public static function fromEntity(AbstractEntity $entity): self
    {
        $dto = parent::fromEntity($entity);

        $dto->body = $entity->getBody();
        $dto->dateCreated = $entity->getDateCreated()?->format(DateTimeInterface::ATOM);
        $dto->room = (string) $entity->getRoom()->getId();
        $dto->type = $entity->getType();

        return $dto;
    }
}
