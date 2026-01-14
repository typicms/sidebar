# TypiCMS Sidebar

This package is based on SpartnerNL/Laravel-Sidebar v1

## Installation

Require this package in your `composer.json` and run `composer update`.

```php
"typicms/sidebar": "~1.0.0"
```

After updating composer, add the ServiceProvider to the providers array in `bootstrap/providers.php`

```php
'TypiCMS\Modules\Sidebar\SidebarServiceProvider::class',
```

To publish the default views use:

```php
php artisan vendor:publish
```

## Adding a menu

```php
// Or use dependency injection
$builder = app('TypiCMS\Modules\Sidebar\SidebarManager');

$builder->group('app', function ($group)
{
    $group->addItem('dashboard', function($item)
    {
        // Route method automatically transforms it into a route
        $item->route('admin::dashboard');

        // If you want to call route() yourself to pass optional parameters
        $item->route = route('admin::dashboard');
    });
}
```

## Groups

It’s possible to group the menu items. A little header will be rendered to separate the different menu items.

## Adding items

The first parameter of `$group->addItem()` is the name. The name field will automatically be translated through the `menu` translation file. (e.g. `menu.dashboard`). If the translation does not exists, the given name will be displayed.

The second parameter is optionally a callback. Alternatively you can chain the methods.

You can change the `route`, `name` and `icon`. If you route given it will automatically be translated to `acp.{$name}.index`.

## Without group headings

To disable rendering of the group headings, you can easily use `$builder->withoutGroup()`. Group headings will now be ignored.

## Authorization

By default, all groups and items are public for all users. You can use `->authorized(false)` on all these objects to disable them or use any condition you want.

## Advanced usage

If you have multiple sidebars, you can extend the SidebarManager and register a new singleton:

```php
class AdminSidebar extends SidebarManager
{
    public function build($callback = null)
    {
        $this->group('application', function(SidebarGroup $group) {

            $group->addItem(...)

        });
    }

}
```
