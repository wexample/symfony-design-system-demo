<?php

namespace Wexample\SymfonyDesignSystemDemo\Checker\Demo;

use Wexample\SymfonyCheck\Checker\AbstractRequiredFieldsChecker;
use Wexample\SymfonyDesignSystemDemo\Class\Demo\GateDemoInvoiceCreation;

/**
 * No invoice until the organization has its title, SIRET and IBAN; the
 * organization form is where they are filled in.
 */
class GateDemoInvoiceChecker extends AbstractRequiredFieldsChecker
{
    public function supports(object $subject): bool
    {
        return $subject instanceof GateDemoInvoiceCreation;
    }

    protected function getTarget(object $subject): ?object
    {
        return $subject->organization;
    }

    protected function getRequiredPaths(): array
    {
        return ['title', 'siret', 'iban'];
    }

    protected function getRemedyRoute(): string
    {
        return 'wexample_design_system_generic_form_gate_organization';
    }
}
