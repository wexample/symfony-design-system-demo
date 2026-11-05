<?php

namespace Wexample\SymfonyDesignSystemDemo\Form\Type\Demo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Wexample\SymfonyForms\Form\Type\TagsInputType;

/**
 * A field standardised: the stamps a message is marked with, as an
 * application would have them — the design system's tags field, its options
 * preset. Drawn by the tags field, posted as it posts, handed to a vue as a
 * `tags` field: nothing of its own but its defaults, each still given way to
 * by a form that names another.
 */
class DemoStampsInputType extends AbstractType
{
    public const array KNOWN = ['urgent', 'follow-up', 'invoice', 'vip', 'late', 'archived'];

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function getParent(): string
    {
        return TagsInputType::class;
    }

    public function getBlockPrefix(): string
    {
        // Drawn by the tags field's block; its own name only where a theme
        // asks for it.
        return 'demo_stamps_input';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'tone' => 'cat-straw',
            'glyph' => 'ph:bold/tag',
            'suggestions_url' => $this->urlGenerator->generate('wexample_design_system_generic_form_stamps'),
        ]);
    }
}
