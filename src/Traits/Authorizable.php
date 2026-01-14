<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Sidebar\Traits;

trait Authorizable
{
    protected bool $authorized = true;

    public function isAuthorized(): bool
    {
        return $this->authorized;
    }

    public function authorize(bool $state = true): self
    {
        $this->authorized = $state;

        return $this;
    }
}
