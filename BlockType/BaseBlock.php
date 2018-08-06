<?php

namespace Opera\CoreBundle\BlockType;

use Symfony\Component\Form\FormBuilderInterface;
use Opera\CoreBundle\Entity\Block;

abstract class BaseBlock
{

    public function getTemplate(Block $block) : string
    {
        return sprintf('blocks/%s.html.twig', $this->getType());
    }

    public function getVariables(Block $block) : array
    {
        return [];
    }

    public function createAdminConfigurationForm(FormBuilderInterface $builder)
    {
    }
}