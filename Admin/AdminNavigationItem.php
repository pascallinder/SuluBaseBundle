<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;

/**
 * Interface for admin classes that provide navigation items.
 *
 * Implemented by {@see AdminCrud} to expose navigation configuration
 * as a static method, allowing other components to reference the navigation
 * structure without instantiating the admin class.
 */
interface AdminNavigationItem
{
    /**
     * Returns the navigation item for this admin class.
     */
    public static function getNavigationItem(): NavigationItem;
}
