<?php

namespace Opera\CoreBundle\Event;

use Opera\CoreBundle\Entity\Block;
use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\Event;

abstract class BlockEvent extends Event
{
    private $blockType;

    private $block;

    private $response;

    public function __construct(BlockTypeInterface $blockType, Block $block)
    {
        $this->block = $block;
        $this->blockType = $blockType;
    }

    public function getBlockType() : BlockTypeInterface
    {
        return $this->blockType;
    }

    public function getBlock() : Block
    {
        return $this->block;
    }
    
}