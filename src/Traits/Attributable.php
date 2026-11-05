<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Sidebar\Traits;

use Illuminate\Support\Str;
use UnexpectedValueException;

trait Attributable
{
    /** @var array<string, mixed> */
    protected array $attributes = [];

    public function cleanInstance(): self
    {
        $instance = $this->container->make($this::class);

        if (! $instance instanceof self) {
            throw new UnexpectedValueException('The container must build a '.$this::class.'.');
        }

        return $instance;
    }

    public function setAttribute(string $attribute, mixed $value): self
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    public function getAttribute(string $attribute, ?string $value = null): mixed
    {
        $value = $this->getRawAttribute($attribute, $value);

        if ($this->hasMutator($attribute)) {
            return $this->mutate($attribute, is_string($value) ? $value : null);
        }

        return $value;
    }

    protected function getRawAttribute(string $attribute, ?string $value = null): mixed
    {
        return $this->attributes[$attribute] ?? $value;
    }

    public function hasMutator(string $attribute): bool
    {
        $method = $this->getMutateMethod($attribute);

        return method_exists($this, $method);
    }

    public function mutate(string $attribute, ?string $value): mixed
    {
        $method = $this->getMutateMethod($attribute);

        return $this->{$method}($value);
    }

    protected function getMutateMethod(string $attribute): string
    {
        return 'get'.Str::studly($attribute);
    }

    public function __set(string $attribute, ?string $value): void
    {
        $this->setAttribute($attribute, $value);
    }

    public function __get(string $attribute): ?string
    {
        $value = $this->getAttribute($attribute);

        return is_string($value) ? $value : null;
    }

    public function __isset(string $attribute): bool
    {
        return isset($this->attributes[$attribute]);
    }

    /** @param array<int, mixed> $params */
    public function __call(string $method, array $params): self
    {
        return $this->setAttribute($method, head($params));
    }
}
