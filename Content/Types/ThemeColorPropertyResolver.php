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
        return ContentView::create(ThemeColorValue::from($data), $params);
    }

    public static function getType(): string
    {
        return 'color_picker_custom';
    }
}
