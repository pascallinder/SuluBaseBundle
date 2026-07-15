<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Content\Types;

final class ThemeColorValue implements \JsonSerializable
{
    public function __construct(
        private string $light = 'inherit',
        private ?string $dark = null
    ) {
    }

    public function getLight(): string
    {
        return $this->light;
    }

    public function getDark(): string
    {
        return $this->dark ?: $this->light;
    }

    public function __toString(): string
    {
        return $this->light;
    }

    public function jsonSerialize(): array
    {
        return [
            'light' => $this->light,
            'dark' => $this->getDark(),
        ];
    }
}
