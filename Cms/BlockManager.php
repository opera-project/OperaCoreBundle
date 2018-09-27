<?php

namespace Opera\CoreBundle\Cms;

use Opera\CoreBundle\Entity\Block;
use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpFoundation\Response;

class BlockManager
{
    private $blockTypes = [];

    private $twig;

    private $formFactory;

    private $context;

    public function __construct(\Twig_Environment $twig, FormFactoryInterface $formFactory, Context $context)
    {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->context = $context;
    }

    public function render(Block $block) : string
    {
        if (!$this->isValidBlockType($block)) {
            throw new \LogicException('Cms cant manage this kind of blocks '.$block->getType());
        }

        $blockType = $this->blockTypes[$block->getType()];

        $ctrlVariables = $blockType->execute($block);

        if ($ctrlVariables instanceof Response) {
            $this->context->addResponse($ctrlVariables);

            return $ctrlVariables->getContent();
        }
        
        $variables = array_merge(
            $this->context->toArray(),
            $ctrlVariables, 
            [
                'block' => $block,
            ]
        );

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

        $this->cleanBlockConfiguration($block);

        return $builder->getForm();
    }

    public function cleanBlockConfiguration(Block $block) : Block
    {
        $config = $this->validateBlockConfiguration($block);

        $block->setConfiguration($config['configuration']);
        $block->setName($config['name']);

        return $block;
    }

    protected function validateBlockConfiguration(Block $block) : array
    {
        if (!$this->isValidBlockType($block)) {
            throw new \LogicException('Cms cant manage this kind of blocks '.$block->getType());
        }

        $blockType = $this->blockTypes[$block->getType()];
        
        $rootNode = new ArrayNodeDefinition('root');
        $blockNode   = $rootNode->children();
        $blockNode->scalarNode('name')->isRequired()->end();

        $configNode = $blockNode->arrayNode('configuration');
        $blockType->configure($configNode);

        $processor = new Processor();
        
        return $processor->process($rootNode->getNode(), [
            'root' => [
                'name' => $block->getName(),
                'configuration' => $block->getConfiguration(),
            ]
        ]);
    }

    public function getKindsOfBlocks()
    {
        return array_keys($this->blockTypes);
    }
}