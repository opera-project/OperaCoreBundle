<?php

namespace Opera\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Opera\CoreBundle\Cms\BlockManager;
use Symfony\Component\DependencyInjection\Reference;

class BlockTypePass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $manager = $container->getDefinition(BlockManager::class);

        // or processing tagged services:
        foreach ($container->findTaggedServiceIds('cms.block_type') as $id => $tags) {
            $manager->addMethodCall('registerBlockType', [ new Reference($id) ]);   
        }
    }
}