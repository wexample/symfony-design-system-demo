<?php

namespace Wexample\SymfonyDesignSystemDemo\Form\Demo;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Wexample\SymfonyForms\Form\AbstractForm;
use Wexample\SymfonyForms\Form\Type\TextareaInputType;
use Wexample\SymfonyForms\Form\Type\TextInputType;

/**
 * A title and its description, never drawn as a form — handed to the design
 * system's vues (`form_props()`): the title edited in place (`inline-edit`),
 * or the form whole (`props-form`), sent in the background.
 */
class FormInlineEditDemoForm extends AbstractForm
{
    public static bool $ajax = true;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextInputType::class, [
                self::FIELD_OPTION_NAME_LABEL => true,
                self::FIELD_OPTION_NAME_REQUIRED => true,
                'constraints' => [new NotBlank(), new Length(max: 40)],
            ])
            ->add('description', TextareaInputType::class, [
                self::FIELD_OPTION_NAME_LABEL => true,
                self::FIELD_OPTION_NAME_REQUIRED => false,
                'constraints' => [new Length(max: 500)],
            ]);
    }
}
