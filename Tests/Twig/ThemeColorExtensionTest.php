<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Tests\Twig;

use Linderp\SuluBaseBundle\Content\Types\ThemeColorValue;
use Linderp\SuluBaseBundle\Twig\ThemeColorExtension;
use PHPUnit\Framework\TestCase;

final class ThemeColorExtensionTest extends TestCase
{
    private ThemeColorExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new ThemeColorExtension();
    }

    public function testItRendersBothThemeColors(): void
    {
        self::assertSame(
            'light-dark(#ffffff, #111111)',
            $this->extension->themeColor(new ThemeColorValue('#ffffff', '#111111')),
        );
    }

    public function testItAvoidsAnUnnecessaryLightDarkFunctionForOneColor(): void
    {
        self::assertSame('#ffffff', $this->extension->themeColor('#ffffff'));
    }

    public function testItSupportsStoredArrayValues(): void
    {
        self::assertSame(
            'light-dark(#ffffff, #111111)',
            $this->extension->themeColor(['light' => '#ffffff', 'dark' => '#111111']),
        );
    }

    public function testItCanReplaceInheritForTransparentUnderlays(): void
    {
        self::assertSame(
            'light-dark(transparent, #111111)',
            $this->extension->themeColor(['light' => 'inherit', 'dark' => '#111111'], 'transparent'),
        );
    }

    public function testItUsesCurrentColorAsTheValidCssDefaultForInherit(): void
    {
        self::assertSame(
            'light-dark(currentColor, #111111)',
            $this->extension->themeColor(['light' => 'inherit', 'dark' => '#111111']),
        );
    }
}
