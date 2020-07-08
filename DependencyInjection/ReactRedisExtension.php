<?php

declare(strict_types=1);

namespace Morbo\React\Redis\DependencyInjection;

use Morbo\React\Loop\DependencyInjection\ReactLoopExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ReactRedisExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (! $container->has('react.loop')) {
            $extension = new ReactLoopExtension();
            $extension->load([], $container);
        }

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yml');

        $definition = $container->getDefinition('react.redis');
        $definition->replaceArgument(2, $config['redis']['dsn']);
    }
}