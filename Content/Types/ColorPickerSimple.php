<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Content\Types;

use Sulu\Component\Content\SimpleContentType;

/**
 * Simple color picker content type for single color values.
 */
final class ColorPickerSimple extends SimpleContentType
{
    public function __construct()
    {
        parent::__construct('color_picker_simple', 'inherit');
    }
}
