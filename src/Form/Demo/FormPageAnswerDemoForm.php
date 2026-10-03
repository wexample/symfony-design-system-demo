<?php

namespace Wexample\SymfonyDesignSystemDemo\Form\Demo;

use Symfony\Component\Form\FormBuilderInterface;
use Wexample\SymfonyForms\Form\AbstractForm;
use Wexample\SymfonyForms\Form\Type\TextInputType;

/**
 * A form whose answer is a page: what it issues is shown once, by the page
 * answering the submission — see its processor.
 */
class FormPageAnswerDemoForm extends AbstractForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('label', TextInputType::class, [
            self::FIELD_OPTION_NAME_LABEL => true,
            self::FIELD_OPTION_NAME_REQUIRED => true,
        ]);

        $this->builderAddSubmit($builder);
    }
}
