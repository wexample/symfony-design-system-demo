<?php

namespace Wexample\SymfonyDesignSystemDemo\Form\Type\Demo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Wexample\SymfonyForms\Form\Type\SearchSelectInputType;

/**
 * A country, one among too many to send with the form: the design system's
 * searched select with its source preset — the demo's list of countries,
 * asked as the name is typed. The value is the country's code.
 */
class DemoCountryInputType extends AbstractType
{
    public const array COUNTRIES = [
        'AR' => 'Argentina', 'AU' => 'Australia', 'AT' => 'Austria', 'BE' => 'Belgium', 'BR' => 'Brazil',
        'CA' => 'Canada', 'CL' => 'Chile', 'CN' => 'China', 'CO' => 'Colombia', 'CZ' => 'Czechia',
        'DK' => 'Denmark', 'EG' => 'Egypt', 'FI' => 'Finland', 'FR' => 'France', 'DE' => 'Germany',
        'GR' => 'Greece', 'IN' => 'India', 'ID' => 'Indonesia', 'IE' => 'Ireland', 'IT' => 'Italy',
        'JP' => 'Japan', 'KE' => 'Kenya', 'MX' => 'Mexico', 'MA' => 'Morocco', 'NL' => 'Netherlands',
        'NZ' => 'New Zealand', 'NG' => 'Nigeria', 'NO' => 'Norway', 'PE' => 'Peru', 'PL' => 'Poland',
        'PT' => 'Portugal', 'KR' => 'South Korea', 'ES' => 'Spain', 'SE' => 'Sweden', 'CH' => 'Switzerland',
        'TH' => 'Thailand', 'TR' => 'Türkiye', 'UA' => 'Ukraine', 'GB' => 'United Kingdom', 'US' => 'United States',
        'VN' => 'Vietnam',
    ];

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function getParent(): string
    {
        return SearchSelectInputType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'demo_country_input';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'options_url' => $this->urlGenerator->generate('wexample_design_system_generic_form_countries'),
        ]);
    }

    /**
     * The countries holding what is typed, by their name or their code, as
     * `{ value, label }`.
     *
     * @return list<array{value: string, label: string}>
     */
    public static function search(string $typed): array
    {
        $typed = mb_strtolower(trim($typed));
        $found = [];

        foreach (self::COUNTRIES as $code => $name) {
            if ('' === $typed || str_contains(mb_strtolower($name), $typed) || mb_strtolower($code) === $typed) {
                $found[] = ['value' => $code, 'label' => $name];
            }
        }

        return $found;
    }
}
