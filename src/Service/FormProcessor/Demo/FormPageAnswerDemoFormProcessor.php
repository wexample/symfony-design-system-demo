<?php

namespace Wexample\SymfonyDesignSystemDemo\Service\FormProcessor\Demo;

use Symfony\Component\Form\FormInterface;
use Wexample\SymfonyForms\Service\FormProcessor\AbstractFormProcessor;
use Wexample\SymfonyHelpers\Helper\RoleHelper;

/**
 * Issues a code shown once — the way a token's secret is —, left on the
 * request for the page answering this very submission: no payload, no
 * redirect, no session carries it. Sent from a modal, the page comes back in
 * the modal (`answersWithPage()`, and the page keeping its navigation).
 */
class FormPageAnswerDemoFormProcessor extends AbstractFormProcessor
{
    final public const string ATTRIBUTE_CODE = '_demo_issued_code';

    public function getRequiredRoles(): array
    {
        return [RoleHelper::PUBLIC_ACCESS];
    }

    public function answersWithPage(): bool
    {
        return true;
    }

    public function onValid(FormInterface $form): void
    {
        $this->request?->attributes->set(self::ATTRIBUTE_CODE, bin2hex(random_bytes(8)));
    }
}
