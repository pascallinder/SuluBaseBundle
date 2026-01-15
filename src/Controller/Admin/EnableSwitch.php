<?php
namespace Linderp\SuluBase\Controller\Admin;
use Symfony\Component\HttpFoundation\Request;

trait EnableSwitch
{
    protected function triggerSwitch(Request $request, string $action, $entity): void
    {
        switch ($action) {
            case 'enable':
                $entity->setEnabled(true);
                break;
            case 'disable':
                $entity->setEnabled(false);
                break;
        }
    }
}