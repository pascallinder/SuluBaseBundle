<?php

namespace Linderp\SuluBase\Admin;

interface EnableToggleAdmin
{
    public function getEnableLabel():string;
    public function getEnableProperty():string;
}