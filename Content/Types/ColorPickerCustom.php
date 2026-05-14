<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Content\Types;

use Sulu\Component\Content\SimpleContentType;
use Sulu\Component\Content\Compat\PropertyInterface;

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
        parent::__construct('color_picker_custom', ['light' => 'inherit', 'dark' => null]);
    }

    public function getViewData(PropertyInterface $property): array
    {
        return $this->normalizeColorValue($property->getValue());
    }

    public function getContentData(PropertyInterface $property): ThemeColorValue
    {
        $value = $this->normalizeColorValue($property->getValue());

        return new ThemeColorValue($value['light'], $value['dark']);
    }

    protected function encodeValue($value): string
    {
        return \json_encode($this->normalizeColorValue($value), \JSON_THROW_ON_ERROR);
    }

    protected function decodeValue($value): array
    {
        return $this->normalizeColorValue($value);
    }

    /**
     * @param mixed $value
     *
     * @return array{light: string, dark: ?string}
     */
    private function normalizeColorValue($value): array
    {
        if ($value instanceof ThemeColorValue) {
            return [
                'light' => $value->getLight(),
                'dark' => $value->getDark() !== $value->getLight() ? $value->getDark() : null,
            ];
        }

        if (\is_array($value)) {
            $light = $this->normalizeColorString($value['light'] ?? null);
            $dark = $this->normalizeOptionalColorString($value['dark'] ?? null);

            return [
                'light' => $light,
                'dark' => $dark,
            ];
        }

        if (\is_string($value)) {
            $decoded = \json_decode($value, true);
            if (\JSON_ERROR_NONE === \json_last_error() && \is_array($decoded)) {
                return $this->normalizeColorValue($decoded);
            }

            return [
                'light' => $this->normalizeColorString($value),
                'dark' => null,
            ];
        }

        return [
            'light' => 'inherit',
            'dark' => null,
        ];
    }

    private function normalizeColorString(?string $value): string
    {
        if (null === $value || '' === \trim($value)) {
            return 'inherit';
        }

        return $value;
    }

    private function normalizeOptionalColorString(?string $value): ?string
    {
        if (null === $value || '' === \trim($value)) {
            return null;
        }

        return $value;
    }
}
