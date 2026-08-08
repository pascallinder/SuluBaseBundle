<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Tests\Content\Types;

use Linderp\SuluBaseBundle\Content\Types\ThemeColorValue;
use PHPUnit\Framework\TestCase;

final class ThemeColorValueTest extends TestCase
{
    /**
     * @dataProvider storedValueProvider
     */
    public function testItNormalizesStoredValues(mixed $storedValue, string $light, string $dark): void
    {
        $value = ThemeColorValue::from($storedValue);

        self::assertSame($light, $value->getLight());
        self::assertSame($dark, $value->getDark());
    }

    /** @return iterable<string, array{mixed, string, string}> */
    public function storedValueProvider(): iterable
    {
        yield 'array' => [['light' => '#ffffff', 'dark' => '#111111'], '#ffffff', '#111111'];
        yield 'json' => ['{"light":"#ffffff","dark":"#111111"}', '#ffffff', '#111111'];
        yield 'single color' => ['#ffffff', '#ffffff', '#ffffff'];
        yield 'empty colors' => [['light' => ' ', 'dark' => ''], 'inherit', 'inherit'];
        yield 'unsupported value' => [false, 'inherit', 'inherit'];
    }

    public function testItReturnsAnExistingValueWithoutCopyingIt(): void
    {
        $value = new ThemeColorValue('#ffffff', '#111111');

        self::assertSame($value, ThemeColorValue::from($value));
    }

    public function testItSerializesTheEffectiveDarkColor(): void
    {
        $value = new ThemeColorValue('#ffffff');

        self::assertSame(['light' => '#ffffff', 'dark' => '#ffffff'], $value->jsonSerialize());
        self::assertSame('#ffffff', (string) $value);
    }

    public function testItBuildsCssWithAnExplicitInheritFallback(): void
    {
        $value = new ThemeColorValue('inherit', '#111111');

        self::assertSame('light-dark(transparent, #111111)', $value->toCss('transparent'));
    }
}
