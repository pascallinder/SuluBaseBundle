<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Admin;

/**
 * Marker interface for admin classes that support enable/disable toggle.
 *
 * When implemented by an {@see AdminCrud} subclass, automatically adds
 * an enable/disable toggle button to the edit form toolbar.
 *
 * Example:
 * ```php
 * class MyAdmin extends AdminCrud implements AdminEnableToggle
 * {
 *     public function getEnableLabel(): string { return 'app.enable_my_entity'; }
 *     public function getEnableProperty(): string { return 'enabled'; }
 * }
 * ```
 */
interface AdminEnableToggle
{
    /**
     * Returns the translation key for the enable toggle label.
     */
    public function getEnableLabel(): string;

    /**
     * Returns the entity property name that stores the enabled state.
     */
    public function getEnableProperty(): string;
}
