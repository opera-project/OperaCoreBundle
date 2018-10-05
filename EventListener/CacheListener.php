<?php

namespace Opera\CoreBundle\EventListener;

use Opera\CoreBundle\Cms\CacheManager;
use Opera\CoreBundle\Cms\BlockManager;
use Opera\CoreBundle\Event\BlockPerRenderEvent;
use Opera\CoreBundle\Event\BlockPostRenderEvent;
use Opera\CoreBundle\Event\BlockPostBuildFormEvent;
use Opera\CoreBundle\Event\BlockPostConfigureEvent;
use Opera\CoreBundle\Event\BlockUpdatedEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Opera\CoreBundle\BlockType\CacheableBlockInterface;
use Opera\CoreBundle\Entity\Block;

class CacheListener implements EventSubscriberInterface
{
    private $cacheManager;
    
    private $blockManager;

    public function __construct(CacheManager $cacheManager, BlockManager $blockManager)
    {
        $this->cacheManager = $cacheManager;
        $this->blockManager = $blockManager;
    }

    public function onBlockPreRender(BlockPerRenderEvent $event)
    {
        $blockType = $event->getBlockType();
        $block = $event->getBlock();

        if (!$this->cacheManager->isCacheable($blockType)) {
            return;
        }

        $cached = $this->cacheManager->get($blockType, $block);
        if (false !== $cached) {
            $response = new Response($cached);

            $event->setResponse($response);
        }
    }

    public function onBlockPostRender(BlockPostRenderEvent $event)
    {
        $blockType = $event->getBlockType();
        $block = $event->getBlock();

        if (!$this->cacheManager->isCacheable($blockType)) {
            return;
        }

        $this->cacheManager->write($blockType, $block, $event->getContent());
    }

    public function onBlockPostBuildAdminForm(BlockPostBuildFormEvent $event)
    {
        $blockType = $event->getBlockType();
        $block = $event->getBlock();

        if (!$blockType instanceof CacheableBlockInterface) {
            return;
        }

        $builder = $event->getFormBuilder();

        $cacheBuilder = $builder->create('cache', FormType::class, [
            'required' => false,
        ]);
        $cacheBuilder->add('expires_after');
        $cacheBuilder->add('vary_path_info', CheckboxType::class, [
            'label' => 'Vary with path',
            'translation_domain' => 'OperaAdminBundle',
        ]);
        $cacheBuilder->add('vary_query_string', CheckboxType::class, [
            'label' => 'Vary include query string',
            'translation_domain' => 'OperaAdminBundle',
        ]);

        $builder->add($cacheBuilder);
    }

    public function onBlockPostConfigure(BlockPostConfigureEvent $event)
    {
        $configNode = $event->getConfigurationNode();

        if (!$event->getBlockType() instanceof CacheableBlockInterface) {
            return;
        }

        $cacheNode = $configNode->children()->arrayNode('cache')->children();

        $configNode
            ->children()
                ->arrayNode('cache')
                    ->children()
                        ->scalarNode('expires_after')->defaultValue('5 minutes')->end()
                        ->booleanNode('vary_path_info')->defaultTrue()->end()
                        ->booleanNode('vary_query_string')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end();
    }

    public function onBlockUpdated(BlockUpdatedEvent $event)
    {
        $this->cacheManager->invalidateByTag($event->getBlockType(), $event->getBlock());
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'opera.block.pre_render' => 'onBlockPreRender',
            'opera.block.post_render' => 'onBlockPostRender',
            'opera.block.post_build_admin_form' => 'onBlockPostBuildAdminForm',
            'opera.block.post_configure' => 'onBlockPostConfigure',
            'opera.block.updated' => 'onBlockUpdated',
        ];
    }
}