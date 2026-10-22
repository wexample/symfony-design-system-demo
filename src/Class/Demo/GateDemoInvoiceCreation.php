<?php

namespace Wexample\SymfonyDesignSystemDemo\Class\Demo;

/**
 * Making an invoice, as the subject its gate checks.
 */
final readonly class GateDemoInvoiceCreation
{
    public function __construct(
        public GateDemoOrganization $organization,
    ) {
    }
}
