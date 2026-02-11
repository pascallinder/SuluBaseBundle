<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Admin;

/**
 * Marker interface for admin classes that should not appear in top-level navigation.
 *
 * When implemented by an {@see AdminCrud} subclass, prevents the automatic
 * addition of a navigation item. Use this for child entities that should
 * only be accessible through parent entity detail views.
 *
 * Example:
 * ```php
 * class ChildAdmin extends AdminCrud implements AdminChild
 * {
 *     // No navigation item will be created
 * }
 * ```
 */
interface AdminChild {}
