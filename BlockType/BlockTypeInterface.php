<?php

namespace Opera\CoreBundle\BlockType;

use Symfony\Component\Form\FormBuilderInterface;
use Opera\CoreBundle\Entity\Block;

interface BlockTypeInterface
{
    public function getType() : string;

    public function getTemplate(Block $block) : string;
    
    public function getVariables(Block $block) : array;
    
    public function createAdminConfigurationForm(FormBuilderInterface $builder);
}