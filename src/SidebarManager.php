<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Sidebar;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\ResolvesRouteDependencies;
use Illuminate\Support\Collection;
use ReflectionFunction;
use Stringable;

class SidebarManager implements Stringable
{
    use ResolvesRouteDependencies;

    protected bool $withoutGroupHeading = false;

    /** @var Collection<string, SidebarGroup> */
    public Collection $groups;

    public function __construct(
        protected Container $container,
        protected SidebarGroup $group,
    ) {
        $this->groups = new Collection();
    }

    public function build(?Closure $callback = null): SidebarManager
    {
        if ($callback instanceof Closure) {
            call_user_func($callback, $this);
        }

        return $this;
    }

    public function withoutGroup(): SidebarManager
    {
        $this->withoutGroupHeading = true;

        return $this;
    }

    public function isWithoutGroupHeading(): bool
    {
        return $this->withoutGroupHeading;
    }

    public function group(string $name, ?Closure $callback = null): SidebarGroup
    {
        $group = $this->groupExists($name) ? $this->getGroup($name) : $this->group->init($name);

        if ($callback instanceof Closure) {
            // Make dependency injection possible
            $parameters = $this->resolveMethodDependencies(['group' => $group], new ReflectionFunction($callback));

            call_user_func_array($callback, $parameters);
        }

        // Add the group to our menu groups
        if ($group) {
            $this->setGroup($name, $group);
        }

        // Return the group object
        return $group;
    }

    public function render(): string
    {
        $html = '<ul class="sidebar-menu">';

        // Order by weight
        $groups = $this->groups->sortBy('weight');

        foreach ($groups as $group) {
            // Don't overrule user preferences
            if (!isset($group->hideHeading)) {
                $group->hideHeading($this->withoutGroupHeading);
            }

            $html .= $group->render();
        }

        return $html . '</ul>';
    }

    public function groupExists(string $name): bool
    {
        return $this->groups->has($this->getNameKey($name));
    }

    public function getGroup(string $name): mixed
    {
        return $this->groups->get($this->getNameKey($name));
    }

    public function setGroup(string $name, SidebarGroup $group): void
    {
        $this->groups->put($this->getNameKey($name), $group);
    }

    public function __toString(): string
    {
        return $this->render();
    }

    protected function getNameKey(string $name): string
    {
        return md5($name);
    }

    /** @return Collection<string, SidebarGroup> */
    public function getGroups(): Collection
    {
        return $this->groups;
    }
}
