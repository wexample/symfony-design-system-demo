<?php

namespace Wexample\SymfonyDesignSystemDemo\Form\Demo;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Wexample\SymfonyDesignSystemDemo\Form\Type\Demo\DemoCountryInputType;
use Wexample\SymfonyDesignSystemDemo\Form\Type\Demo\DemoStampsInputType;
use Wexample\SymfonyForms\Form\AbstractForm;
use Wexample\SymfonyForms\Form\Type\ColorInputType;
use Wexample\SymfonyForms\Form\Type\KeyValueInputType;
use Wexample\SymfonyForms\Form\Type\NumberInputType;
use Wexample\SymfonyForms\Form\Type\SwitchInputType;
use Wexample\SymfonyForms\Form\Type\PasswordInputType;
use Wexample\SymfonyForms\Form\Type\SelectInputType;
use Wexample\SymfonyForms\Form\Type\TextareaInputType;
use Wexample\SymfonyForms\Form\Type\TextInputType;

/**
 * One field of every kind the json-schema norm names, never drawn as a form:
 * handed to the design system's `dynamic-form` (`form_definition()`), which draws
 * each by its kind and sends them to the processor, which refuses them all —
 * each error at its place is what the page shows.
 */
class FormFromServerDemoForm extends AbstractForm
{
    public static bool $ajax = true;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $field = [self::FIELD_OPTION_NAME_LABEL => true];

        $builder
            ->add('title', TextInputType::class, $field + [
                self::FIELD_OPTION_NAME_REQUIRED => true,
                'constraints' => [new NotBlank(), new Length(max: 40)],
                'attr' => ['placeholder' => true],
            ])
            ->add('summary', TextareaInputType::class, $field + [
                self::FIELD_OPTION_NAME_REQUIRED => false,
                'attr' => ['rows' => 3],
                // Its earlier versions, the bar above the field.
                'versions_url' => $this->urlGenerator->generate('wexample_design_system_generic_form_versions'),
            ])
            ->add('email', EmailType::class, $field + [self::FIELD_OPTION_NAME_REQUIRED => false])
            ->add('phone', TelType::class, $field + [self::FIELD_OPTION_NAME_REQUIRED => false])
            ->add('website', UrlType::class, $field + [self::FIELD_OPTION_NAME_REQUIRED => false, 'default_protocol' => null])
            ->add('secret', PasswordInputType::class, $field + [self::FIELD_OPTION_NAME_REQUIRED => false])
            ->add('audience', SelectInputType::class, $field + [
                'choices' => ['everyone', 'members', 'board'],
                self::FIELD_OPTION_NAME_REQUIRED => true,
            ])
            ->add('color', ColorInputType::class, $field + [
                self::FIELD_OPTION_NAME_REQUIRED => false,
                'swatches' => ['#e8ce4d' => 'Straw', '#4fc1e9' => 'Aqua', '#ed5565' => 'Grapefruit', '#8067b7' => 'Plum'],
            ])
            ->add('country', DemoCountryInputType::class, $field + [
                self::FIELD_OPTION_NAME_REQUIRED => false,
                'value_label' => DemoCountryInputType::COUNTRIES['FR'],
            ])
            ->add('stamps', DemoStampsInputType::class, $field + [
                self::FIELD_OPTION_NAME_REQUIRED => false,
            ])
            ->add('pages', NumberInputType::class, $field + [
                self::FIELD_OPTION_NAME_REQUIRED => false,
                'stepper' => true,
                'attr' => ['min' => 1, 'max' => 40],
            ])
            ->add('public', SwitchInputType::class, $field + [
                self::FIELD_OPTION_NAME_REQUIRED => false,
            ])
            ->add('metadata', KeyValueInputType::class, $field + [
                self::FIELD_OPTION_NAME_REQUIRED => false,
                'data' => ['lang' => 'en', 'channel' => 'web'],
            ]);
    }
}
