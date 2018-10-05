<?php

namespace Opera\CoreBundle\Event;

use Opera\CoreBundle\Entity\Block;
use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class BlockPostConfigureEvent extends BlockEvent
{
    private $configNode;

    public function __construct(BlockTypeInterface $blockType, Block $block, ArrayNodeDefinition $configNode)
    {
        parent::__construct($blockType, $block);
        $this->configNode = $configNode;
    }

    public function getConfigurationNode() : ArrayNodeDefinition
    {
        return $this->configNode;
    }
}