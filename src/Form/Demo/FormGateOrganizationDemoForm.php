<?php

namespace Wexample\SymfonyDesignSystemDemo\Form\Demo;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wexample\SymfonyDesignSystemDemo\Class\Demo\GateDemoOrganization;
use Wexample\SymfonyForms\Form\AbstractForm;
use Wexample\SymfonyForms\Form\Type\TextInputType;

/**
 * Where the gate demo sends to complete the organization. No field is
 * required here: a half-filled organization saves, and the gate sends back.
 */
class FormGateOrganizationDemoForm extends AbstractForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach (['title', 'siret', 'iban'] as $field) {
            $builder->add($field, TextInputType::class, [
                self::FIELD_OPTION_NAME_LABEL => true,
                self::FIELD_OPTION_NAME_REQUIRED => false,
            ]);
        }

        $this->builderAddSubmit($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('data_class', GateDemoOrganization::class);
    }
}
