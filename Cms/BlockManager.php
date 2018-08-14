<?php

namespace Opera\CoreBundle\Cms;

use Opera\CoreBundle\Entity\Block;
use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;

class BlockManager
{
    private $blockTypes = [];

    private $twig;

    private $formFactory;

    public function __construct(\Twig_Environment $twig, FormFactoryInterface $formFactory)
    {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
    }

    public function render(Block $block) : string
    {
        if (!$this->isValidBlockType($block)) {
            throw new \LogicException('Cms cant manage this kind of blocks '.$block->getType());
        }

        $blockType = $this->blockTypes[$block->getType()];

        $variables = array_merge($blockType->getVariables($block), [
            'block' => $block,
        ]);

        return $this->twig->render(
            $blockType->getTemplate($block),
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

    public function createAdminForm(Block $block) : Form
    {
        if (!$this->isValidBlockType($block)) {
            throw new \LogicException('Cms cant manage this kind of blocks '.$block->getType());
        }

        $builder = $this->formFactory->createNamedBuilder('block_'.$block->getId(), FormType::class, $block);

        // Common fields
        $builder->add('name');

        // Configuration fields
        $blockType = $this->blockTypes[$block->getType()];

        $configurationBuilder = $builder->create('configuration', FormType::class);
        $blockType->createAdminConfigurationForm($configurationBuilder);
        
        if ($configurationBuilder->count()) {
            $builder->add($configurationBuilder)
                    ->get('configuration');
        }

        return $builder->getForm();
    }

    public function getKindsOfBlocks()
    {
        return array_keys($this->blockTypes);
    }
}