<?php

namespace Linderp\SuluBaseBundle\Admin;

final readonly class AdminCrudConfig
{


    public function __construct(
        public string                    $resourceKey,
        public AdminCrudNavigationConfig $nav,
        public AdminCrudListConfig       $list,
        public AdminCrudFormConfig       $form
    ) {}
}
