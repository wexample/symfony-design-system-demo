# symfony-design-system-demo

Version: 1.0.4

A Symfony bundle that ships the showcase pages of `wexample/symfony-design-system`: a set of Twig pages under assets/pages/design_system/generic rendering buttons, inputs, menus, tables, modals, banners, spinners and forms in their real markup, each exposed as a route by a `#[TemplateBasedRoutes]` controller so the whole catalogue is browsable in a running application. Install it in a host project and the pages mount under the design system base route, grouped by controls, layout, content, components, dialog, feedback and form. It exists for the people building or integrating the design system — to see a component rendered, compare its variants and states side by side, and exercise the interactive ones (AJAX form submission, modal behaviours) against live code rather than a screenshot.

## Table of Contents

- [Architecture](#architecture)
- [Integration in the Suite](#integration-in-the-suite)
- [Dependencies](#dependencies)
- [Versioning & Compatibility Policy](#versioning--compatibility-policy)
- [License](#license)
- [About us](#about-us)
- [Migration Notes](#migration-notes)

## Architecture

The package is a Symfony bundle with no application behind it: its output is a tree of showcase pages mounted under one route prefix. Two directories carry everything — `src/`, where the PHP classes are rarely longer than twenty lines, and `assets/`, which holds the templates, styles, scripts and translations of every page.

### The bundle publishes its asset root

src/WexampleSymfonyDesignSystemDemoBundle.php extends `AbstractBundle` and implements `LoaderBundleInterface`. Its only method hands the loader the asset directory:

```php
public static function getLoaderFrontPaths(): array
{
    return [
        BundleHelper::getBundleCssAlias(static::class) => __DIR__ . '/../assets/',
    ];
}
```

That single mapping is why every reference in the package reads `@WexampleSymfonyDesignSystemDemoBundle/pages/…` and lands in `assets/pages/…`. There is no `templates/` directory; `assets/` is it.

`WexampleSymfonyDesignSystemDemoExtension::load()` does nothing but `$this->loadConfig(__DIR__, $container)`, which pulls in src/Resources/config/services.yaml and src/Resources/config/routes.yaml. The services file autowires three namespaces — `Controller\`, `Form\`, `Service\` — and tags form processors through `_instanceof`:

```yaml
_instanceof:
    Wexample\SymfonyForms\Service\FormProcessor\AbstractFormProcessor:
        tags: ['wexample.symfony_forms.form_processor']
```

### The template tree is the route table

src/Resources/config/routes.yaml loads the controllers by attribute, then a second loader over the same bundle:

```yaml
template_routes:
    resource: .
    type: template_based_routes
```

Most controllers are consequently empty. src/Controller/Pages/DesignSystem/GenericController.php is a class body with nothing in it, carrying two attributes:

```php
#[Route(
    name: 'wexample_design_system_generic_',
    path: AbstractDesignSystemController::CONTROLLER_BASE_ROUTE . '/generic/',
)]
#[TemplateBasedRoutes]
final class GenericController extends AbstractDesignSystemGenericController
```

One controller per page section — `ComponentsController`, `ContentController`, `ControlsController`, `DialogController`, `FeedbackController`, `FormController`, `LayoutController` — each with a `wexample_design_system_generic_<section>_` name prefix and a `/generic/<section>/` path, each matching a directory of the same name under `assets/pages/design_system/generic/`. Adding `foo.html.twig` to one of those directories adds the route `wexample_design_system_generic_<section>_foo`; no PHP is touched.

They all extend `AbstractDesignSystemGenericController`, which exists only to carry `SymfonyDesignSystemDemoBundleClassTrait` — the trait that answers `getBundleClassName()`, telling the renderer which bundle's asset root to look in.

Explicit methods appear only where a page needs the server. `DialogController` declares five of them, all shaped alike:

```php
#[Route(path: 'modal-test-simple', name: self::ROUTE_MODAL_TEST_SIMPLE, options: AbstractController::ROUTE_OPTIONS_ONLY_EXPOSE)]
public function modalTestSimple(): Response
{
    return $this->renderPage(self::ROUTE_MODAL_TEST_SIMPLE);
}
```

These are fragments meant to be loaded into a modal or panel, referenced by route name from JavaScript rather than linked in the navigation.

### One page, four co-located files

A page is a basename repeated across extensions in the same directory. `components/doc` is `doc.html.twig`, `doc.scss`, `doc.ts`, `doc.en.yml`. Nothing imports the last three explicitly — the loader pairs them with the template by name.

The template extends its section layout and addresses its own translations through the `@page::` alias:

```twig
{%- extends '@WexampleSymfonyDesignSystemDemoBundle/pages/design_system/generic/components/layout/layout-components.html.twig' -%}

{%- block page_body -%}
    <p class="lead">{{ '@page::intro' | trans }}</p>
{%- endblock -%}
```

The `.ts` default-exports a class extending the loader's `Page`, and does its work in `pageReady()`:

```ts
import Page from '@wexample/symfony-loader/js/Class/Page';

export default class extends Page {
  async pageReady() {
    await initCodeBlocks(this.el);
  }
}
```

The `.scss` is usually a one-line `@use` of a shared partial (`dialog/index.scss` is `@use './page-dialog';`), and the `_`-prefixed files — `_page-dialog.scss`, `_layout-components.scss` — pull in the shapes the section needs from the upstream design system:

```scss
@use '@wexample/symfony-design-system/css/shapes/tab';
```

### Layouts, and the seam with the host application

Each section owns a layout at `<section>/layout/layout-<section>.html.twig`. It extends the bundle layout and its job is the tab bar, one `tab_item(translation_key, route_name)` per page:

```twig
{{ tab_item('WexampleSymfonyDesignSystemDemoBundle.pages.design_system.generic.components.layout.layout-components::tab.doc', 'wexample_design_system_generic_components_doc') }}
```

All section layouts converge on assets/layouts/design_system/layout.html.twig, which is where the package stops being self-contained:

```twig
{%- extends '@front/layouts/private/layout.html.twig' -%}
```

`@front` is the host application's own template namespace. The demo supplies pages; the application supplies the chrome around them. The matching `layout.en.yml` inherits the same way, with `~extends: '@front.layouts.private.layout'`.

### What a request goes through

For `components/doc`: the template-based route loader matched the URL because the file exists; `ComponentsController` handles it without a method of its own and the page renderer takes over; `doc.html.twig` extends `layout-components.html.twig`, which extends the demo layout, which extends the application's private layout; `render_pass` — threaded through `vue()`, `vue_include()`, `form_load()` and `render_pass.layoutRenderNode.setDefaultView(_self)` — accumulates what the page needs; on the client, the `Page` subclass from `doc.ts` is instantiated and `pageReady()` runs against `this.el`.

### Forms: one definition, three renderings

src/Form/Demo/FormSubmitBehaviorDemoForm.php builds one field of every type `wexample/symfony-forms` offers. Every field is `mapped => false` with a hardcoded `data`, so the form displays filled without an entity behind it, and the submit buttons encode the demo's four outcomes: `submit_error`, `submit_js`, `submit_redirect`, `submit_default`. Two more appear when `AdaptiveRequestHelper::isEmbedded($request)` is true.

`FormSubmitBehaviorAjaxDemoForm` subclasses it and changes two things — `public static bool $ajax = true;` and the translation domain. `FormSubmitBehaviorAjaxDemoFormProcessor` likewise only overrides `getFormClass()`.

The outcome is chosen in src/Service/FormProcessor/Demo/FormSubmitBehaviorDemoFormProcessor.php, from the clicked button:

```php
$behavior = match(true) {
    $form->has('submit_error') && $form->get('submit_error')->isClicked() => 'error',
    ...
};
```

`FormController` wires processor to page with an attribute rather than a service call:

```php
#[FormProcessor(
    processorClass: FormSubmitBehaviorDemoFormProcessor::class,
    formArgumentName: 'form_submit_behavior_demo'
)]
```

The three pages diverge only in transport. `rendered` and `ajax` hand the form view to `form_load(render_pass, form_submit_behavior_demo, form_template)` against assets/forms/demo/form_submit_behavior_demo_form.html.twig; `vue` renders a Vue component that posts JSON to the `test` route, a `match` on `$data['behavior']` returning canned payloads (`type: error` with a field-error summary, `redirect`, `js_action`, `success`).

The same four behaviors are therefore spelled out in four places — the processor's `onValid()`, the `test()` endpoint, `form_submit_behavior_demo_form.ts` (`onBeforeSubmit`, `handleSuccessAction`) and `demo-form.vue`. Changing one without the others makes the pages disagree.

### Vue components

`assets/vue/` holds pairs. The `.vue.twig` is the server half: it extends a base from the design system bundle, declares its dependencies, and fills blocks with translated markup.

```twig
{%- extends '@WexampleSymfonyDesignSystemBundle/vue/bases/form.vue.twig' -%}
{{- vue_require(render_pass, '@WexampleSymfonyDesignSystemBundle/vue/form/fields/text-input') -}}
```

Some are nothing else — `demo-entity-table.vue.twig` is a single `extends` line. The `.vue` is the client half: an options object that `extends` an upstream component and binds to the rendered template by id. assets/vue/collection/table/demo-entity-table.vue overrides `refreshEntitiesCollection()` to slice a 37-row constant instead of calling an API, and points its row actions back at the dialog routes:

```js
{ name: 'show', route: 'wexample_design_system_generic_dialog_modal_test_simple' },
```

### What is not here

No tests, no build configuration, no entities, no migrations. `composer.json` requires only `wexample/symfony-design-system` and `wexample/symfony-forms`, yet the code imports `Wexample\SymfonyLoader`, `Wexample\SymfonyHelpers`, `Wexample\SymfonyRouting` and `@wexample/symfony-content` directly — they arrive transitively, and a version bump upstream can break this package without any declared constraint noticing.

## Integration in the Suite

This package is part of the Wexample Suite — a collection of high-quality, modular tools designed to work seamlessly together across multiple languages and environments.

### Related Packages

The suite includes packages for configuration management, file handling, prompts, and more. Each package can be used independently or as part of the integrated suite.

Visit the [Wexample Suite documentation](https://docs.wexample.com) for the complete package ecosystem.

## Dependencies

- php: >=8.2
- wexample/symfony-design-system: >=6.0.0
- wexample/symfony-forms: >=2.0.0

## Versioning & Compatibility Policy

Wexample packages follow **Semantic Versioning** (SemVer):

- **MAJOR**: Breaking changes
- **MINOR**: New features, backward compatible
- **PATCH**: Bug fixes, backward compatible

We maintain backward compatibility within major versions and provide clear migration guides for breaking changes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Free to use in both personal and commercial projects.

## About us

[Wexample](https://wexample.com) stands as a cornerstone of the digital ecosystem — a collective of seasoned engineers, researchers, and creators driven by a relentless pursuit of technological excellence. More than a media platform, it has grown into a vibrant community where innovation meets craftsmanship, and where every line of code reflects a commitment to clarity, durability, and shared intelligence.

This packages suite embodies this spirit. Trusted by professionals and enthusiasts alike, it delivers a consistent, high-quality foundation for modern development — open, elegant, and battle-tested. Its reputation is built on years of collaboration, refinement, and rigorous attention to detail, making it a natural choice for those who demand both robustness and beauty in their tools.

Wexample cultivates a culture of mastery. Each package, each contribution carries the mark of a community that values precision, ethics, and innovation — a community proud to shape the future of digital craftsmanship.

## Migration Notes

When upgrading between major versions, refer to the migration guides in the documentation.

Breaking changes are clearly documented with upgrade paths and examples.
