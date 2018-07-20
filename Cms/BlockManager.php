<?php

namespace Opera\CoreBundle\Cms;

use Opera\CoreBundle\Entity\Block;
use Opera\CoreBundle\BlockType\BlockTypeInterface;

class BlockManager
{
    private $blockTypes = [];

    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(Block $block) : string
    {
        if (!$this->isValidBlockType($block)) {
            throw new \LogicException('Cms cant manage this kind of blocks '.$block->getType());
        }

        $blockType = $this->blockTypes[$block->getType()];

        $variables = array_merge($blockType->getVariables(), [
            'block' => $block,
        ]);

        return $this->twig->render(
            sprintf('blocks/%s.html.twig', $blockType->getTemplate()),
            $variables
        );
    }

    public function registerBlockType(BlockTypeInterface $blockType)
    {
        $this->blockTypes[$blockType->getType()] = $blockType;
    }

    public function isValidBlockType(Block $block) : bool
    {
        return isset($this->blockTypes[$block->getType()]);
    }
}