<?php

namespace Wexample\SymfonyDesignSystemDemo\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Wexample\SymfonyDesignSystemDemo\Entity\Traits\Manipulator\DemoRoomEntityManipulatorTrait;
use Wexample\SymfonyHelpers\Voter\AbstractEntityVoter;

/**
 * A demo room is public: the showcase has no accounts, and the live subscription
 * would be denied without a voter saying so — being open to subscription is the
 * attribute's word, who may listen is this one's.
 */
class DemoRoomVoter extends AbstractEntityVoter
{
    use DemoRoomEntityManipulatorTrait;

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token
    ): bool {
        return $attribute === self::VIEW;
    }
}
