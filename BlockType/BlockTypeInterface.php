<?php

namespace Opera\CoreBundle\BlockType;

use Symfony\Component\Form\FormBuilderInterface;

interface BlockTypeInterface
{
    public function getType() : string;

    public function getTemplate() : string;
    
    public function getVariables() : array;
    
    public function createAdminConfigurationForm(FormBuilderInterface $builder);
}