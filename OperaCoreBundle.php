<?php

namespace Opera\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Opera\CoreBundle\DependencyInjection\Compiler\BlockTypePass;
use Opera\CoreBundle\DependencyInjection\Compiler\ResourceCompilerPass;
use Opera\CoreBundle\BlockType\BlockTypeInterface;

class OperaCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(BlockTypeInterface::class)->addTag('cms.block_type');
        $container->addCompilerPass(new BlockTypePass());
        $container->addCompilerPass(new ResourceCompilerPass());
    }
}