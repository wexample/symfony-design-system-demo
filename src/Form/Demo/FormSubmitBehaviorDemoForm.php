<?php

namespace Wexample\SymfonyDesignSystemDemo\Form\Demo;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Length;
use Wexample\Helpers\Helper\PlaceholderHelper;
use Wexample\SymfonyDesignSystemDemo\Repository\DemoMessageRepository;
use Wexample\SymfonyForms\Form\AbstractForm;
use Wexample\SymfonyForms\Form\Type\ButtonInputType;
use Wexample\SymfonyForms\Form\Type\DateInputType;
use Wexample\SymfonyForms\Form\Type\DatetimeInputType;
use Wexample\SymfonyForms\Form\Type\EmailInputType;
use Wexample\SymfonyForms\Form\Type\EmojiPickerType;
use Wexample\SymfonyForms\Form\Type\FileInputType;
use Wexample\SymfonyForms\Form\Type\NumberInputType;
use Wexample\SymfonyForms\Form\Type\PasswordInputType;
use Wexample\SymfonyForms\Form\Type\RadioInputType;
use Wexample\SymfonyForms\Form\Type\SubmitInputType;
use Wexample\SymfonyForms\Form\Type\SwitchInputType;
use Wexample\SymfonyForms\Form\Type\TextareaInputType;
use Wexample\SymfonyForms\Form\Type\TextInputType;
use Wexample\SymfonyForms\Form\Type\TimeInputType;
use Wexample\SymfonyForms\Form\Type\UrlInputType;
use Wexample\SymfonyLoader\Helper\AdaptiveRequestHelper;
use Wexample\SymfonySearch\Form\Type\EntitySearchInputType;

class FormSubmitBehaviorDemoForm extends AbstractForm
{
    public static bool $ajax = false;

    public function __construct(
        private readonly RequestStack $requestStack,
        // The records the list without a box opens on: it offers no way of
        // adding one, so something has to have put them there.
        private readonly DemoMessageRepository $messageRepository,
    ) {
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $request = $this->requestStack->getCurrentRequest();
        $isEmbedded = $request ? AdaptiveRequestHelper::isEmbedded($request) : false;

        $builder
            ->add(
                'hidden_demo',
                HiddenType::class,
                [
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'data' => 'hidden_value',
                ]
            )
            ->add(
                'password',
                PasswordInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => false,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    'data' => 'demo-password',
                ]
            )
            ->add(
                'emoji',
                EmojiPickerType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    'data' => '😊',
                ]
            )
            ->add(
                'text_simple',
                TextInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    'data' => 'Demo text',
                ]
            )
            ->add(
                'text_area',
                TextareaInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'rows' => 4,
                    'max_rows' => 12,
                    'auto_resize' => true,
                    'help' => true,
                    'data' => 'Demo long text for the textarea field.',
                    // A limit, for the field to count what is left of it.
                    'constraints' => [new Length(max: 140)],
                    'attr' => [
                        'placeholder' => true,
                    ],
                ]
            )
            ->add(
                'radio_choice',
                RadioInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'choices' => ['option_a', 'option_b', 'option_c'],
                    'help' => true,
                    'data' => 'option_a',
                ]
            )
            ->add(
                'entity_search',
                EntitySearchInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => false,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    // The rooms of the chat demo, which carry `#[Searchable]`:
                    // the field asks for one of those and never offers a page.
                    EntitySearchInputType::OPTION_ENTITY_TYPE => 'demo_room',
                    'placeholder' => true,
                ]
            )
            ->add(
                'entity_search_multiple',
                EntitySearchInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => false,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    // The messages of the chat demo, there being enough of them
                    // to pick several: one capsule per record, and the box
                    // gone once the third is in.
                    EntitySearchInputType::OPTION_ENTITY_TYPE => 'demo_message',
                    EntitySearchInputType::OPTION_MULTIPLE => true,
                    EntitySearchInputType::OPTION_MAX_VALUES => 3,
                    'placeholder' => true,
                ]
            )
            ->add(
                'entity_search_list',
                EntitySearchInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => false,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    // The same records, without the box: what a relation filled
                    // by something else looks like — an uploader's attachments
                    // — where the field is only how one is taken back off.
                    EntitySearchInputType::OPTION_ENTITY_TYPE => 'demo_message',
                    EntitySearchInputType::OPTION_MULTIPLE => true,
                    EntitySearchInputType::OPTION_SEARCH => false,
                    EntitySearchInputType::OPTION_REMOVE_CONFIRM => true,
                    'data' => $this->messageRepository->findBy([], ['dateCreated' => 'DESC'], 2),
                ]
            )
            ->add(
                'switch',
                SwitchInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    'data' => true,
                ]
            )
            ->add(
                'date',
                DateInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    'data' => new \DateTime('2000-01-15'),
                ]
            )
            ->add(
                'datetime',
                DatetimeInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    'data' => new \DateTime('2000-01-15 09:00:00'),
                ]
            )
            ->add(
                'time',
                TimeInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    'data' => new \DateTime('1970-01-01 09:00:00'),
                ]
            )
            ->add(
                'file',
                FileInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => false,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                ]
            )
            ->add(
                'email',
                EmailInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    'data' => PlaceholderHelper::DEMO_EMAIL,
                    'attr' => ['placeholder' => true],
                ]
            )
            ->add(
                'number',
                NumberInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    'data' => 42,
                ]
            )
            ->add(
                'url',
                UrlInputType::class,
                [
                    self::FIELD_OPTION_NAME_LABEL => true,
                    self::FIELD_OPTION_NAME_REQUIRED => true,
                    self::FIELD_OPTION_NAME_MAPPED => false,
                    'help' => true,
                    'data' => PlaceholderHelper::DEMO_URL,
                    'attr' => ['placeholder' => true],
                ]
            )
            ->add(
                'button_demo',
                ButtonInputType::class,
                [self::FIELD_OPTION_NAME_LABEL => 'action.button_demo']
            )
        ;

        $builder
            ->add('submit_error', SubmitInputType::class, [self::FIELD_OPTION_NAME_LABEL => 'action.submit_error', 'icon' => 'ph:bold/warning'])
            ->add('submit_js', SubmitInputType::class, [self::FIELD_OPTION_NAME_LABEL => 'action.submit_js', 'icon' => 'ph:bold/code'])
            ->add('submit_redirect', SubmitInputType::class, [self::FIELD_OPTION_NAME_LABEL => 'action.submit_redirect', 'icon' => 'ph:bold/arrow-bend-up-right'])
            ->add('submit_default', SubmitInputType::class, [self::FIELD_OPTION_NAME_LABEL => 'action.submit_default', 'primary' => true])
        ;

        if ($isEmbedded) {
            $builder
                ->add('submit_embed_stay', SubmitInputType::class, [self::FIELD_OPTION_NAME_LABEL => 'action.submit_embed_stay', 'icon' => 'ph:bold/arrow-u-up-left'])
                ->add('submit_embed_redirect', SubmitInputType::class, [self::FIELD_OPTION_NAME_LABEL => 'action.submit_embed_redirect', 'icon' => 'ph:bold/arrow-square-out'])
            ;
        }
    }
}
