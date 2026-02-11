<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;

/**
 * Provides standard implementation of enable/disable toggle functionality.
 *
 * Requires the entity to have a `setEnabled(bool): void` method.
 * Use in conjunction with {@see \Linderp\SuluBaseBundle\Admin\AdminEnableToggle}.
 *
 * @template T of object
 */
trait EnableSwitch
{
    /**
     * Toggles the enabled state of an entity.
     *
     * @param T $entity Entity with setEnabled(bool) method
     */
    protected function triggerSwitch(Request $request, string $action, $entity)
    {
        $enabled = match ($action) {
            'enable' => true,
            'disable' => false,
            default => null,
        };

        if ($enabled !== null) {
            $entity->setEnabled($enabled);
        }
    }
}
