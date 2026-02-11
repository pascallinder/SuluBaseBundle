# SuluBaseBundle

Shared Sulu CMS utilities for admin CRUD scaffolding, locale-aware repositories/controllers, and custom admin field types. Use this bundle from your app code to avoid reimplementing standard Sulu admin patterns.

## What This Bundle Provides
- **Admin CRUD base**: `Admin/AdminCrud.php` plus config value objects for list/form/navigation setup.
- **Enable toggle**: `Admin/AdminEnableToggle.php` + `Controller/Admin/EnableSwitch.php` for standard on/off actions.
- **Locale-aware stack**: `Entity/LocaleTrait.php`, `Repository/LocaleRepositoryUtil.php`, and `Controller/Admin/LocaleController.php`.
- **List builder helper**: `Common/DoctrineListRepresentationFactory.php` for paginated list responses.
- **Content types**: `Content/Types/*` registered in `Resources/config/services.yaml`.
- **Admin React fields**: `Resources/js/src/components/content/types/*` registered in `Resources/js/src/app.js`.

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