<?php

namespace Linderp\SuluBase\Admin;

final readonly class AdminCrudListConfig
{


    public function __construct(
        public string $title,
        public string $key,
        public string $view
    ) {}
}