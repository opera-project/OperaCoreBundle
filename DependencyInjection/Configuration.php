<?php

namespace Opera\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * opera_media:
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('opera_core');

        $rootNode
            ->children()
                ->scalarNode('route_prefix')
                    ->defaultValue('/')
                    ->info('To prefix all your cms routes if you deploy under subfolder and dont want to change in db')
                ->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
    

}