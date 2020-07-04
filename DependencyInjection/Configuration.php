<?php

namespace Morbo\React\Redis\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('react');
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC for symfony/config < 4.2
            $rootNode = $treeBuilder->root('react');
        }

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('redis')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('dsn')->defaultValue('redis://localhost')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}