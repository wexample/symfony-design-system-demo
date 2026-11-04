<?php

namespace Wexample\SymfonyDesignSystemDemo\Service\FormProcessor\Demo;

use Symfony\Component\Form\FormInterface;
use Wexample\SymfonyForms\Service\FormProcessor\AbstractFormProcessor;
use Wexample\SymfonyHelpers\Helper\RoleHelper;

/**
 * Saves nothing — a demo. Sent « refuse » as a title, every field is refused
 * with an error of its own, and the form as a whole: what each kind shows
 * once refused. Anything else is taken, and said so.
 */
class FormFromServerDemoFormProcessor extends AbstractFormProcessor
{
    private const string TITLE_REFUSE = 'refuse';

    public function getRequiredRoles(): array
    {
        return [RoleHelper::PUBLIC_ACCESS];
    }

    public function formIsValid(FormInterface $form): bool
    {
        if (! parent::formIsValid($form)) {
            return false;
        }

        if (self::TITLE_REFUSE !== mb_strtolower(trim((string) $form->get('title')->getData()))) {
            return true;
        }

        foreach (array_keys($form->all()) as $name) {
            if ('_token' !== $name) {
                $this->addFieldError($form, $name, 'refused');
            }
        }

        $this->addFormErrorFromApiKey($form, 'refused');

        return false;
    }

    public function onValid(FormInterface $form): void
    {
        $this->setNotification('@form::success.message');
    }
}
