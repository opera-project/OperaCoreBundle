<?php

namespace Opera\CoreBundle\Event;

use Opera\CoreBundle\Entity\Block;
use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Symfony\Component\Form\FormBuilder;

class BlockPostBuildFormEvent extends BlockEvent
{
    private $formBuilder;

    public function __construct(BlockTypeInterface $blockType, Block $block, FormBuilder $formBuilder)
    {
        parent::__construct($blockType, $block);
        $this->formBuilder = $formBuilder;
    }

    public function getFormBuilder()
    {
        return $this->formBuilder;
    }

}