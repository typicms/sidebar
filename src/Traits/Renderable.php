<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Sidebar\Traits;

trait Renderable
{
    public function render(): string
    {
        if ($this->isAuthorized()) {
            return $this->factory
                ->make($this->getView(), [
                    $this->getRenderType() => $this,
                ])
                ->render();
        }

        return '';
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function setView(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function getRenderType(): string
    {
        return $this->renderType;
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
