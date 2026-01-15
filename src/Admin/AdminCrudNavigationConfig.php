<?php

namespace Linderp\SuluBase\Admin;

final readonly class AdminCrudNavigationConfig
{
    public function __construct(
        public string $title,
        public int $position,
        public string $icon
    ) {}
}