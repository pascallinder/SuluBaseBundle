<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Dependency injection extension for SuluBaseBundle.
 *
 * Loads service definitions from Resources/config/services.yaml.
 */
final class SuluBaseExtension extends Extension
{
    /**
     * @param array<int, array<string, mixed>> $configs
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
}
