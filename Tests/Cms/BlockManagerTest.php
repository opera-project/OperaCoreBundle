<?php

namespace Opera\CoreBundle\Tests\Cms;

use Opera\CoreBundle\Tests\TestCase;
use Opera\CoreBundle\Cms\BlockManager;
use Opera\CoreBundle\Entity\Block;
use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Opera\CoreBundle\BlockType\BaseBlock;

class TextBlockType extends BaseBlock implements BlockTypeInterface
{
    public $variables = [];

    public $type;

    public function getType() : string
    {
        return 'text';
    }

    public function getTemplate() : string
    {
        if ($this->type) {
            return $this->type;
        }

        return parent::getTemplate();
    }

    public function getVariables() : array
    {
        return $this->variables;
    }
}

class BlockManagerTest extends TestCase
{
    private $manager;

    public function setUp()
    {
        $this->manager = new BlockManager(
            new \Twig_Environment(
                new \Twig_Loader_Filesystem(__DIR__.'/../templates')
            )
        );
    }

    public function testIsValidBlockType()
    {
        $block = new Block;
        $block->setType('text');

        $this->assertFalse($this->manager->isValidBlockType($block), 'A non registered block type is considered as invalid');

        $this->manager->registerBlockType(new TextBlockType());
        $this->assertTrue($this->manager->isValidBlockType($block), 'A registered block type is considered as valid');
    }

    /**
     * @expectedException \LogicException
     */
    public function testRenderInvalidBlock()
    {
        $block = new Block;
        $block->setType('invalid');

        $this->manager->render($block);
    }

    public function testRenderTextBlock()
    {
        $block = new Block;
        $block->setType('text');
        $block->setConfiguration([
            'text' => 'Hello from text',
        ]);

        $this->manager->registerBlockType(new TextBlockType());

        $this->assertEquals('Hello from text', $this->manager->render($block));
    }

    public function testRenderVariablesBlock()
    {
        $block = new Block;
        $block->setType('text');
        $block->setConfiguration([
            'text' => 'Hello from text',
        ]);
        $type = new TextBlockType();
        $type->variables = [ 'a_custom' => 'variable is in template' ];
        $type->type = 'text_with_variable';
        
        $this->manager->registerBlockType($type);

        $this->assertEquals('variable is in template', $this->manager->render($block));
    }
}