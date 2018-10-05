<?php

namespace Opera\CoreBundle\BlockType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Opera\CoreBundle\Entity\Block;

interface CacheableBlockInterface
{
    public function getCacheConfig(OptionsResolver $resolver, Block $block);
}