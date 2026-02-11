<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Content\Types;

use Sulu\Component\Content\SimpleContentType;

/**
 * Range picker content type for Sulu CMS.
 *
 * Registered as 'range_picker' via service tags.
 * Use in page templates for numeric range selection fields.
 */
final class RangePicker extends SimpleContentType
{
    public function __construct()
    {
        parent::__construct('range_picker');
    }
}
