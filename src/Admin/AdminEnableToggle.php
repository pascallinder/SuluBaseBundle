<?php

namespace Linderp\SuluBaseBundle\Admin;

interface AdminEnableToggle
{
    public function getEnableLabel():string;
    public function getEnableProperty():string;
}