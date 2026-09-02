<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Content\Types;

final class ThemeColorValue implements \JsonSerializable
{
    public function __construct(
        private string $light = 'inherit',
        private ?string $dark = null
    ) {}

    public function getLight(): string
    {
        return $this->light;
    }

    public function getDark(): string
    {
        return $this->dark ?: $this->light;
    }

    public static function from(mixed $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        if (is_array($value)) {
            return new self(
                self::normalizeColor($value['light'] ?? null),
                self::normalizeOptionalColor($value['dark'] ?? null),
            );
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return self::from($decoded);
            }

            return new self(self::normalizeColor($value));
        }

        return new self();
    }

    public function toCss(string $inheritFallback = 'currentColor'): string
    {
        $light = $this->replaceInherit($this->light, $inheritFallback);
        $dark = $this->replaceInherit($this->getDark(), $inheritFallback);

        if ($light === $dark) {
            return $light;
        }

        return sprintf('light-dark(%s, %s)', $light, $dark);
    }

    public function __toString(): string
    {
        return $this->light;
    }

    /** @return array{light: string, dark: string} */
    public function jsonSerialize(): array
    {
        return [
            'light' => $this->light,
            'dark' => $this->getDark(),
        ];
    }

    private static function normalizeColor(mixed $value): string
    {
        return is_string($value) && trim($value) !== '' ? $value : 'inherit';
    }

    private static function normalizeOptionalColor(mixed $value): ?string
    {
        return is_string($value) && trim($value) !== '' ? $value : null;
    }

    private function replaceInherit(string $color, string $inheritFallback): string
    {
        if ('inherit' === $color) {
            return $inheritFallback;
        }

        return $color;
    }
}
