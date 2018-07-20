<?php

namespace Opera\CoreBundle\BlockType;

use Symfony\Component\Form\FormBuilderInterface;

abstract class BaseBlock implements BlockTypeInterface
{
    public function getTemplate() : string
    {
        return sprintf('blocks/%s.html.twig', $this->getType());
    }

    public function getVariables() : array
    {
        return [];
    }

    public function createAdminConfigurationForm(FormBuilderInterface $builder)
    {
    }
}