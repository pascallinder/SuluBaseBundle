<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Content\Types;

use Sulu\Content\Application\ContentResolver\Value\ContentView;
use Sulu\Content\Application\PropertyResolver\Resolver\PropertyResolverInterface;

/**
 * Keeps the website representation of the custom theme color field compatible
 * with the former SimpleContentType implementation.
 */
final class ThemeColorPropertyResolver implements PropertyResolverInterface
{
    public function resolve(mixed $data, string $locale, array $params = []): ContentView
    {
        if ($data instanceof ThemeColorValue) {
            return ContentView::create($data, $params);
        }

        if (is_array($data)) {
            $light = $this->normalizeColor($data['light'] ?? null);
            $dark = $this->normalizeOptionalColor($data['dark'] ?? null);

            return ContentView::create(new ThemeColorValue($light, $dark), $params);
        }

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                return $this->resolve($decoded, $locale, $params);
            }

            return ContentView::create(new ThemeColorValue($this->normalizeColor($data)), $params);
        }

        return ContentView::create(new ThemeColorValue(), $params);
    }

    public static function getType(): string
    {
        return 'color_picker_custom';
    }

    private function normalizeColor(mixed $value): string
    {
        return is_string($value) && trim($value) !== '' ? $value : 'inherit';
    }

    private function normalizeOptionalColor(mixed $value): ?string
    {
        return is_string($value) && trim($value) !== '' ? $value : null;
    }
}
