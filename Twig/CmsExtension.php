<?php

namespace Opera\CoreBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Opera\CoreBundle\Repository\BlockRepository;
use Opera\CoreBundle\Entity\Page;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Opera\CoreBundle\Cms\BlockManager;
use Opera\CoreBundle\Cms\Context;
use Twig\Environment as TwigEnvironment;

class CmsExtension extends AbstractExtension
{
    private $blockRepository;

    private $blockManager;

    private $cmsContext;

    private $sfTwig;

    public function __construct(BlockRepository $blockRepository, BlockManager $blockManager, Context $cmsContext, TwigEnvironment $sfTwig)
    {
        $this->blockRepository = $blockRepository;
        $this->blockManager = $blockManager;
        $this->cmsContext = $cmsContext;
        $this->sfTwig = $sfTwig;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cms_area', [$this, 'cmsArea'], [ 'is_safe' => ['html'] ]),
            new TwigFunction('cms_render', [$this, 'render'], [ 'is_safe' => ['html'] ]),
        ];
    }

    /**
     * cms_area('area_name', page)
     * cms_area('area_name') for global page
     */
    public function cmsArea(string $areaName, ?Page $page = null) : string
    {
        $blocks = $this->blockRepository->findForAreaAndPage($areaName, $page);
        
        $out = '';

        foreach ($blocks as $block) {
            $out .= sprintf('<!-- block id="block_%s" -->', $block->getId());

            $out .= $this->blockManager->render($block);

            $out .= sprintf('<!-- /block id="block_%s" -->', $block->getId());
        }

        return $out;
    }

    /**
     * cms_render('twig {{ code }}')
     * cms_render('twig {{ code }}', { complement: 'more context var' })
     */
    public function render(string $twigTemplate, array $block = array()) : string
    {   
        $template = $this->sfTwig->createTemplate($twigTemplate);

        return $template->render(
            array_merge(
                $this->cmsContext->toArray(),
                $block
            )
        );
    }
}