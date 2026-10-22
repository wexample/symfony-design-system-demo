<?php

namespace Wexample\SymfonyDesignSystemDemo\Service\Demo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Wexample\SymfonyDesignSystemDemo\Class\Demo\GateDemoOrganization;
use Wexample\SymfonyForms\Service\FormProcessor\FormProcessorDataResolverInterface;

/**
 * The gate demo's organization, one per visitor, in the session: nothing to
 * migrate, nothing shared. It is also the data its form is built on.
 */
class GateDemoOrganizationStore implements FormProcessorDataResolverInterface
{
    private const string SESSION_KEY = 'design_system_demo.gate_organization';

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function load(): GateDemoOrganization
    {
        $organization = new GateDemoOrganization();

        foreach ($this->requestStack->getSession()->get(self::SESSION_KEY, []) as $field => $value) {
            if (property_exists($organization, $field)) {
                $organization->$field = $value;
            }
        }

        return $organization;
    }

    public function save(GateDemoOrganization $organization): void
    {
        $this->requestStack->getSession()->set(self::SESSION_KEY, get_object_vars($organization));
    }

    public function reset(): void
    {
        $this->requestStack->getSession()->remove(self::SESSION_KEY);
    }

    public function resolve(
        Request $request,
        array $options = []
    ): mixed {
        return $this->load();
    }
}
