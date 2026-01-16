<?php

namespace Linderp\SuluBaseBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;

interface AdminNavigationItem
{
    public static function getNavigationItem():NavigationItem;
}