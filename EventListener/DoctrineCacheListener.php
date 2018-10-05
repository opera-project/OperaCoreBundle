<?php

namespace Opera\CoreBundle\EventListener;

use Opera\CoreBundle\Cms\CacheManager;
use Opera\CoreBundle\Cms\BlockManager;
use Opera\CoreBundle\Entity\Block;

class DoctrineCacheListener
{
    private $cacheManager;
    
    private $blockManager;

    public function __construct(CacheManager $cacheManager, BlockManager $blockManager)
    {
        $this->cacheManager = $cacheManager;
        $this->blockManager = $blockManager;
    }

    public function preFlush(Block $block)
    {
        $this->cacheManager->invalidateByTag($this->blockManager->getBlockType($block), $block);
    }

}