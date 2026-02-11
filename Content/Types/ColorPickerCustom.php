<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Content\Types;

use Sulu\Component\Content\SimpleContentType;

/**
 * Custom color picker content type for Sulu CMS.
 *
 * Registered as 'color_picker_custom' via service tags.
 * Use in page templates for color selection fields.
 */
final class ColorPickerCustom extends SimpleContentType
{
    public function __construct()
    {
        parent::__construct('color_picker_custom');
    }
}
