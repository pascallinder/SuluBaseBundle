<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Twig;

use Linderp\SuluBaseBundle\Content\Types\ThemeColorValue;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ThemeColorExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('theme_color', [$this, 'themeColor']),
        ];
    }

    public function themeColor(mixed $value, string $inheritFallback = 'currentColor'): string
    {
        return ThemeColorValue::from($value)->toCss($inheritFallback);
    }
}
