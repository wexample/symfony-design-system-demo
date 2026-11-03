<?php

namespace Wexample\SymfonyDesignSystemDemo\Service\FormProcessor\Demo;

use Symfony\Component\Form\FormInterface;
use Wexample\SymfonyForms\Service\FormProcessor\AbstractFormProcessor;
use Wexample\SymfonyHelpers\Helper\RoleHelper;

/**
 * Saves nothing — a demo — and says it did; refuses « taken », as a title
 * already used would be, the error put on the field.
 */
class FormInlineEditDemoFormProcessor extends AbstractFormProcessor
{
    private const string TITLE_TAKEN = 'taken';

    public function getRequiredRoles(): array
    {
        return [RoleHelper::PUBLIC_ACCESS];
    }

    public function formIsValid(FormInterface $form): bool
    {
        if (! parent::formIsValid($form)) {
            return false;
        }

        if (self::TITLE_TAKEN === mb_strtolower(trim((string) $form->get('title')->getData()))) {
            $this->addFieldError($form, 'title', 'taken');

            return false;
        }

        return true;
    }

    public function onValid(FormInterface $form): void
    {
        $this->setNotification('@form::success.message');
    }
}
