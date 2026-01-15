<?php

namespace Linderp\SuluBase\Admin;

final readonly class AdminCrudFormConfig
{
    public function __construct(
        public string $titleProperty,
        public string $addView,
        public string $editView,
        public string $key
    ) {}
}