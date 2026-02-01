<?php

namespace Linderp\SuluBaseBundle\Content\Types;
use Sulu\Component\Content\SimpleContentType;

class RangePicker extends SimpleContentType
{
    public function __construct()
    {
        parent::__construct('range_picker');
    }
}