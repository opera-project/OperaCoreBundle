<?php

namespace Opera\CoreBundle\BlockType;

use Symfony\Component\Form\FormBuilderInterface;
use Opera\CoreBundle\Entity\Block;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

interface BlockTypeInterface
{
    public function getType() : string;

    public function getTemplate(Block $block) : string;
    
    public function execute(Block $block);
    
    public function createAdminConfigurationForm(FormBuilderInterface $builder);

    public function configure(NodeDefinition $rootNode);
}