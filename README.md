# SuluBaseBundle

Shared Sulu CMS utilities for admin CRUD scaffolding, locale-aware repositories/controllers, and custom admin field types. Use this bundle from your app code to avoid reimplementing standard Sulu admin patterns.

## What This Bundle Provides
- **Admin CRUD base**: `Admin/AdminCrud.php` plus config value objects for list/form/navigation setup.
- **Enable toggle**: `Admin/AdminEnableToggle.php` + `Controller/Admin/EnableSwitch.php` for standard on/off actions.
- **Locale-aware stack**: `Entity/LocaleTrait.php`, `Repository/LocaleRepositoryUtil.php`, and `Controller/Admin/LocaleController.php`.
- **List builder helper**: `Common/DoctrineListRepresentationFactory.php` for paginated list responses.
- **Doctrine resource loader**: `Content/AbstractEntityResourceLoader.php` centralizes enabled-entity loading, locale setup, and configured property mapping for Sulu smart content.
- **Content types**: `Content/Types/*` registered in `Resources/config/services.yaml`.
- **Theme-aware Twig colors**: `theme_color(value, inheritFallback)` renders the two-color picker value as CSS `light-dark()`.
- **Admin React fields**: `Resources/js/src/components/content/types/*`, including the `map_picker` field, the read-only `generated_link` property, and the `sulu_base.snackbar` toolbar action registered in `Resources/js/src/app.js`.

## Render Theme Colors in Twig

Use the `theme_color` function for values from `color_picker_custom`. The browser picks the matching value from the document's `color-scheme`:

```twig
<section style="background-color: {{ theme_color(content.bgColor) }}">
    <h2 style="color: {{ theme_color(content.textColor) }}">{{ content.title }}</h2>
</section>
```

`inherit` is represented as `currentColor` by default, which is valid inside CSS `light-dark()` for text, borders, fills, strokes, and shadows. Pass a property-specific replacement as the optional second argument for backgrounds and other properties:

```twig
<div style="background-color: {{ theme_color(content.underlayColor, 'transparent') }}"></div>
```

## Quick Start: Add a New Admin CRUD
1. **Admin class** (extend `AdminCrud`):
   - Implement `define()` using `AdminCrudConfig` + `AdminCrudNavigationConfig` + `AdminCrudListConfig` + `AdminCrudFormConfig`.
   - Implement `AdminEnableToggle` if the entity has an `enabled` flag.
2. **Controller** (extend `BaseController` or `LocaleController`):
   - Implement `getDataForEntity()`, `mapDataToEntity()`, `load()`, `create()`, `save()`, `remove()`.
   - Use `EnableSwitch` trait if you added the enable toggle.
3. **Repository**:
   - Extend `BaseRepositoryUtil` for plain entities or `LocaleRepositoryUtil` for localized entities.
   - For `LocaleRepositoryUtil`, implement `append()` and `appendSortByJoins()` for list/smart content.

## Example: Minimal Admin Definition
```php
final class EventAdmin extends AdminCrud
{
    public static function define(): AdminCrudConfig
    {
        return new AdminCrudConfig(
            'events',
            new AdminCrudNavigationConfig('app.events', 10, 'su-calendar'),
            new AdminCrudListConfig('app.events', 'events', 'app.events_list'),
            new AdminCrudFormConfig('title', 'app.events_add', 'app.events_edit', 'event_form')
        );
    }
}
```
