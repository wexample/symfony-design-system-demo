<?php

namespace Wexample\SymfonyDesignSystemDemo\Form\Demo;

/**
 * The same fields as its parent, submitted over ajax.
 *
 * The translation domain follows the class name, so the two forms read their
 * own labels without either of them naming a domain.
 */
class FormSubmitBehaviorAjaxDemoForm extends FormSubmitBehaviorDemoForm
{
    public static bool $ajax = true;
}
