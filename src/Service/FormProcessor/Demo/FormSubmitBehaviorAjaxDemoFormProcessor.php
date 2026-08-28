<?php

namespace Wexample\SymfonyDesignSystemDemo\Service\FormProcessor\Demo;

use Wexample\SymfonyDesignSystemDemo\Form\Demo\FormSubmitBehaviorAjaxDemoForm;

class FormSubmitBehaviorAjaxDemoFormProcessor extends FormSubmitBehaviorDemoFormProcessor
{
    public static function getFormClass(): string
    {
        return FormSubmitBehaviorAjaxDemoForm::class;
    }
}
