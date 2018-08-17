<?php

namespace Opera\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResourceCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter($parameter = 'twig.form.resources')) {
            $container->setParameter(
                $parameter,
                array_merge(
                    ['@OperaCore/Form/opera_autocomplete_widget.html.twig'],
                    $container->getParameter($parameter)
                )
            );
        }
    }
}
