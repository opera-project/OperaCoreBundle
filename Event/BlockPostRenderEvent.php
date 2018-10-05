<?php

namespace Opera\CoreBundle\Event;

use Opera\CoreBundle\Entity\Block;
use Opera\CoreBundle\BlockType\BlockTypeInterface;

class BlockPostRenderEvent extends BlockEvent
{
    private $contentOrResponse;

    public function __construct(BlockTypeInterface $blockType, Block $block, $contentOrResponse)
    {
        parent::__construct($blockType, $block);
        $this->contentOrResponse = $contentOrResponse;
    }

    public function getContent()
    {
        return $this->contentOrResponse;
    }

}