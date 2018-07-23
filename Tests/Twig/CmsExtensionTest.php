<?php

namespace Opera\CoreBundle\Tests\Twig;

use Opera\CoreBundle\Twig\CmsExtension;
use Opera\CoreBundle\Cms\Context;
use Opera\CoreBundle\Cms\BlockManager;
use Opera\CoreBundle\Repository\BlockRepository;
use Opera\CoreBundle\Tests\TestCase;
use Symfony\Component\Form\FormFactory;

class CmsExtensionTest extends TestCase
{
    protected function runTwigTests(array $templates, array $returns)
    {
        $twig = new \Twig_Environment(
            new \Twig_Loader_Array($templates),
            [
                    'debug' => true,
                    'cache' => false,
                    'autoescape' => false,
            ]
        );

        $cmsContext = new Context();
        $cmsContext->setVariables([
            'hello' => 'hello world',
        ]);
        $formFactory = $this->getMockBuilder(FormFactory::class)
                        ->disableOriginalConstructor()
                        ->getMock();
        $blockManager = new BlockManager($twig, $formFactory);
        $blockRepository = $this->getMockBuilder(BlockRepository::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $extension = new CmsExtension($blockRepository, $blockManager, $cmsContext);

        $twig->addExtension($extension);

        foreach ($templates as $name => $tpl) {
            $this->assertEquals(
                $returns[$name][0],
                $twig->loadTemplate($name)->render([]),
                $returns[$name][1]
            );
        }
    }

    /**
     * cms_render('twig {{ code }}')
     */
    public function testRender()
    {
        $tpls = array(
            'simple_string' => '{{ cms_render("Hello") }}',
            'variable_key' => '{{ cms_render("Say: {{ hello }}") }}',
        );

        $returns = array(
            'simple_string' => array(
                'Hello',
                'A string is not transformed'
            ),
            'variable_key' => array(
                'Say: hello world',
                'Twig get cms contexts variables'
            ),
        );

        $this->runTwigTests($tpls, $returns);
    }
}