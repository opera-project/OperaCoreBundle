<?php

namespace Opera\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Opera\CoreBundle\DependencyInjection\Compiler\BlockTypePass;

class OperaCoreBundle extends Bundle
{
    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new BlockTypePass());
    }
}