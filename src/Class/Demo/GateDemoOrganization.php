<?php

namespace Wexample\SymfonyDesignSystemDemo\Class\Demo;

/**
 * The organization of the gate demo, kept in the session: what an invoice
 * needs filled in before it can be made.
 */
class GateDemoOrganization
{
    public ?string $title = null;

    public ?string $siret = null;

    public ?string $iban = null;
}
