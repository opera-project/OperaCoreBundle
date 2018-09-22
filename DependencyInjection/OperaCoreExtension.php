<?php

namespace Opera\CoreBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class OperaCoreExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config =  $this->processConfiguration($this->getConfiguration($configs, $container), $configs);
        $container->setParameter('opera_core.route_prefix', $config['route_prefix']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

    }
}