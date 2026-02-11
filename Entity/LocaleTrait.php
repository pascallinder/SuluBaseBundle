<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Entity;

/**
 * Provides a locale property for translation entities.
 *
 * Used with {@see LocaleRepositoryUtil} and {@see LocaleController}
 * for multi-locale entity management.
 *
 * Note: This property is not persisted by Doctrine. It's used at runtime
 * to track which translation is currently active.
 */
trait LocaleTrait
{
    protected string $locale;

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }
}
