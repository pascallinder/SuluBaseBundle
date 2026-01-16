<?php

namespace Linderp\SuluBaseBundle\Admin;

final readonly class AdminCrudNavigationConfig
{
    public function __construct(
        public string $title,
        public ?int $position = 0,
        public ?string $icon = null
    ) {}
}