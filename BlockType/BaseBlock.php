<?php

namespace Opera\CoreBundle\BlockType;

use Symfony\Component\Form\FormBuilderInterface;
use Opera\CoreBundle\Entity\Block;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

abstract class BaseBlock
{

    public function getTemplate(Block $block) : string
    {
        return sprintf('blocks/%s.html.twig', $this->getType());
    }

    public function execute(Block $block) : array
    {
        return [];
    }

    public function createAdminConfigurationForm(FormBuilderInterface $builder)
    {
    }

    public function configure(NodeDefinition $rootNode)
    {
        $rootNode->prototype('variable')->end();
    }

}