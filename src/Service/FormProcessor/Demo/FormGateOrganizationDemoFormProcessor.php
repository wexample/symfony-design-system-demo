<?php

namespace Wexample\SymfonyDesignSystemDemo\Service\FormProcessor\Demo;

use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Wexample\SymfonyDesignSystemDemo\Service\Demo\GateDemoOrganizationStore;
use Wexample\SymfonyForms\Service\FormProcessor\AbstractFormProcessor;
use Wexample\SymfonyHelpers\Helper\RoleHelper;

/**
 * Saves the organization. Reached by a gate, the base processor then sends
 * back to the invoice; reached directly, back to the demo page.
 */
class FormGateOrganizationDemoFormProcessor extends AbstractFormProcessor
{
    private GateDemoOrganizationStore $store;

    #[Required]
    public function setStore(GateDemoOrganizationStore $store): void
    {
        $this->store = $store;
    }

    public function getRequiredRoles(): array
    {
        return [RoleHelper::PUBLIC_ACCESS];
    }

    public function onValid(FormInterface $form): void
    {
        $this->store->save($form->getData());
        $this->redirectToRoute('wexample_design_system_generic_form_gate');
    }
}
