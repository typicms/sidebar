<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Sidebar;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\ResolvesRouteDependencies;
use Illuminate\Support\Collection;
use ReflectionFunction;
use TypiCMS\Modules\Sidebar\Traits\Attributable;
use TypiCMS\Modules\Sidebar\Traits\Authorizable;
use TypiCMS\Modules\Sidebar\Traits\Renderable;

class SidebarGroup
{
    use Attributable;
    use Authorizable;
    use Renderable;
    use ResolvesRouteDependencies;

    /** @var Collection<int, SidebarItem> */
    public Collection $items;

    public string $id;

    public int $weight;

    protected string $view = 'sidebar::group';

    protected string $renderType = 'group';

    private bool $hideHeading;

    public function __construct(
        private readonly Container $container,
        protected readonly Factory $factory,
        protected readonly SidebarItem $item,
    ) {}

    public function init(string $name): SidebarGroup
    {
        // Reset the object
        $instance = $this->cleanInstance();
        $instance->setAttribute('name', $name);
        $instance->setAttribute('weight', 1);
        $instance->items = new Collection;

        return $instance;
    }

    public function hideHeading(bool $state = true): bool
    {
        $this->hideHeading = $state;

        return false;
    }

    public function shouldShowHeading(): bool
    {
        return ! $this->hideHeading;
    }

    public function getItem(): SidebarItem
    {
        return $this->item;
    }

    public function addItem(string $name, Closure $callback): SidebarItem
    {
        $item = $this->getItem()->init($name);

        $parameters = $this->resolveMethodDependencies(['item' => $item], new ReflectionFunction($callback));

        call_user_func_array($callback, $parameters);

        // Add the new item to the array
        $this->items->push($item);

        // Return the item object
        return $item;
    }

    public function hasItems(): bool
    {
        return count($this->items) > 0;
    }

    /** @return Collection<int, SidebarItem> */
    public function getItems(): Collection
    {
        return $this->items->sortBy('weight');
    }
}
