<?php

namespace Wexample\SymfonyDesignSystemDemo\Controller\Pages\DesignSystem\Generic;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyDesignSystemDemo\Controller\Pages\DesignSystem\AbstractDesignSystemGenericController;
use Wexample\SymfonyDesignSystemDemo\Service\FormProcessor\Demo\FormPageAnswerDemoFormProcessor;
use Wexample\SymfonyDesignSystemDemo\Service\FormProcessor\Demo\FormSubmitBehaviorAjaxDemoFormProcessor;
use Wexample\SymfonyDesignSystemDemo\Service\FormProcessor\Demo\FormSubmitBehaviorDemoFormProcessor;
use Wexample\SymfonyForms\Attribute\FormProcessor;
use Wexample\SymfonyLoader\Controller\Pages\AbstractDesignSystemController;
use Wexample\SymfonyRouting\Attribute\TemplateBasedRoutes;

#[Route(
    name: 'wexample_design_system_generic_form_',
    path: AbstractDesignSystemController::CONTROLLER_BASE_ROUTE . '/generic/form/',
)]
#[TemplateBasedRoutes]
final class FormController extends AbstractDesignSystemGenericController
{
    // Template-based routes for index and vue are auto-generated.

    // Files dropped or picked, sent in pieces to a directory of the demo's
    // own, under the app's var: the page signs the address for it.
    #[Route(name: 'upload', path: 'upload')]
    public function upload(
        #[\Symfony\Component\DependencyInjection\Attribute\Autowire('%kernel.project_dir%/var/design-system-uploads')]
        string $uploadDir,
    ): Response {
        return $this->renderPage('upload', [
            'upload_dir' => $uploadDir,
            'uploaded' => is_dir($uploadDir) ? array_values(array_diff(scandir($uploadDir) ?: [], ['.', '..'])) : [],
        ]);
    }

    #[Route(name: 'rendered', path: 'rendered')]
    #[FormProcessor(
        processorClass: FormSubmitBehaviorDemoFormProcessor::class,
        formArgumentName: 'form_submit_behavior_demo'
    )]
    public function rendered(
        FormInterface $form_submit_behavior_demo
    ): Response {
        return $this->renderPage('rendered', [
            'form_submit_behavior_demo' => $form_submit_behavior_demo->createView(),
            'form_submit_behavior_submitted' => $form_submit_behavior_demo->isSubmitted() && $form_submit_behavior_demo->isValid(),
        ]);
    }

    #[Route(name: 'test', path: 'test', methods: ['POST'])]
    public function test(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $behavior = $data['behavior'] ?? 'default';

        return match ($behavior) {
            'error' => new JsonResponse([
                'type' => 'error',
                'data' => [
                    'summary' => [
                        'global' => ['ERR_FORM_TEST'],
                        'fields' => [
                            'password' => ['ERR_FIELD_PASSWORD_INVALID'],
                            'emoji' => ['ERR_FIELD_EMOJI_INVALID'],
                            'text_simple' => ['ERR_FIELD_TEXT_SIMPLE_INVALID'],
                            'text_area' => ['ERR_FIELD_TEXT_AREA_INVALID'],
                            'number' => ['ERR_FIELD_NUMBER_INVALID'],
                            'email' => ['ERR_FIELD_EMAIL_INVALID'],
                            'url' => ['ERR_FIELD_URL_INVALID'],
                            'date' => ['ERR_FIELD_DATE_INVALID'],
                            'datetime' => ['ERR_FIELD_DATETIME_INVALID'],
                            'time' => ['ERR_FIELD_TIME_INVALID'],
                            'file' => ['ERR_FIELD_FILE_INVALID'],
                            'radio_choice' => ['ERR_FIELD_RADIO_CHOICE_INVALID'],
                            'switch' => ['ERR_FIELD_SWITCH_INVALID'],
                        ],
                    ],
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY),
            'redirect' => new JsonResponse([
                'type' => 'redirect',
                'url' => '/',
            ]),
            'js' => new JsonResponse([
                'type' => 'js_action',
                'toast' => [
                    'title' => 'JS action',
                    'message' => 'Form submitted successfully (JS).',
                ],
            ]),
            default => new JsonResponse(['type' => 'success']),
        };
    }

    #[Route(name: 'ajax', path: 'ajax')]
    #[FormProcessor(
        processorClass: FormSubmitBehaviorAjaxDemoFormProcessor::class,
        formArgumentName: 'form_submit_behavior_demo'
    )]
    public function ajax(
        FormInterface $form_submit_behavior_demo
    ): Response {
        return $this->renderPage('ajax', [
            'form_submit_behavior_demo' => $form_submit_behavior_demo->createView(),
            'form_submit_behavior_submitted' => $form_submit_behavior_demo->isSubmitted() && $form_submit_behavior_demo->isValid(),
        ]);
    }

    /**
     * A form answered by its page, in a modal as on its own: what it issued
     * is shown by the page answering the submission, once.
     */
    #[Route(name: 'page_answer', path: 'page-answer')]
    #[FormProcessor(
        processorClass: FormPageAnswerDemoFormProcessor::class,
        formArgumentName: 'form_page_answer_demo'
    )]
    public function pageAnswer(
        Request $request,
        FormInterface $form_page_answer_demo
    ): Response {
        return $this->renderPage('page_answer', [
            'form_page_answer_demo' => $form_page_answer_demo->createView(),
            'issued_code' => $request->attributes->get(FormPageAnswerDemoFormProcessor::ATTRIBUTE_CODE),
            'issued_label' => $form_page_answer_demo->isSubmitted() ? $form_page_answer_demo->get('label')->getData() : null,
        ]);
    }
}
