<?php

namespace Wexample\SymfonyDesignSystemDemo\Form\Demo;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Wexample\SymfonyForms\Form\AbstractForm;
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
            ])
            ->add('email', EmailType::class, $field + [self::FIELD_OPTION_NAME_REQUIRED => false])
            ->add('phone', TelType::class, $field + [self::FIELD_OPTION_NAME_REQUIRED => false])
            ->add('website', UrlType::class, $field + [self::FIELD_OPTION_NAME_REQUIRED => false, 'default_protocol' => null])
            ->add('secret', PasswordInputType::class, $field + [self::FIELD_OPTION_NAME_REQUIRED => false])
            ->add('audience', SelectInputType::class, $field + [
                'choices' => ['everyone', 'members', 'board'],
                self::FIELD_OPTION_NAME_REQUIRED => true,
            ]);
    }
}
