<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Sidebar;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\ResolvesRouteDependencies;
use TypiCMS\Modules\Sidebar\Traits\Attributable;
use TypiCMS\Modules\Sidebar\Traits\Authorizable;
use TypiCMS\Modules\Sidebar\Traits\Renderable;

class SidebarItem
{
    use Attributable;
    use Authorizable;
    use Renderable;
    use ResolvesRouteDependencies;

    public string $id;

    public string $icon;

    public int $weight;

    protected string $view = 'sidebar::item';

    protected string $renderType = 'item';

    public function __construct(
        private readonly Container $container,
        private readonly Request $request,
        protected readonly Factory $factory,
    ) {}

    public function init(string $name): SidebarItem
    {
        $instance = $this->cleanInstance();
        $instance->setAttribute('name', $name);
        $instance->setAttribute('weight', 1);

        return $instance;
    }

    public function getItem(): SidebarItem
    {
        return $this;
    }

    public function getState(?string $value = null): ?string
    {
        if (($value === null || $value === '') && $this->checkActiveState()) {
            return 'active';
        }

        return $value;
    }

    protected function checkActiveState(): bool
    {
        // If the active state was manually set
        if (! is_null($this->getAttribute('active'))) {
            return $this->getAttribute('active');
        }

        $path = mb_ltrim(str_replace(url('/'), '', $this->getAttribute('route')), '/');

        return $this->request->is($path, $path.'/*');
    }

    /** @param array<string, mixed> $params */
    public function route(string $route, array $params = []): self
    {
        return $this->setAttribute('route', route($route, $params));
    }
}
