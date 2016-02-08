<?php

namespace Webfactory\TestBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Defines the configuration for this bundle.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('webfactory_test');

        $rootNode
            ->children()
                ->scalarNode('required_parameter')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('A required parameter without default value.')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
