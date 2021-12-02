<?php

namespace Opera\CoreBundle\Cms;

use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Opera\CoreBundle\BlockType\CacheableBlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Opera\CoreBundle\Entity\Block;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Gedmo\Sluggable\Util\Urlizer;

class CacheManager
{
    private $cacheItemPool;
    
    private $requestStack;

    private $isCacheActive;

    public function __construct(TagAwareAdapterInterface $cacheItemPool, RequestStack $requestStack, $isCacheActive)
    {
        $this->cacheItemPool = $cacheItemPool;
        $this->requestStack = $requestStack;
        $this->isCacheActive = $isCacheActive;
    }

    public function isCacheable(BlockTypeInterface $blockType) : bool
    {
        if (!$blockType instanceof CacheableBlockInterface) {
            return false;
        }

        return $this->isCacheActive && $this->requestStack->getCurrentRequest()->isMethod('GET');
    }

    private function getCacheConfig(BlockTypeInterface $blockType, Block $block) : array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('cache_key');
        $resolver->setRequired('expires_after');
        $resolver->setRequired('vary');

        $resolver->setDefaults([
            'cache_key' => 'block_'.$blockType->getType().'_'.$block->getId(),
            'expires_after' => '5 minutes',
            'vary' => '',
            'vary_path_info'    => true,
            'vary_query_string' => true,
        ]);

        $blockType->getCacheConfig($resolver, $block);

        return $resolver->resolve($block->getConfiguration()['cache'] ?? []);
    }

    private function getCacheKeyWithVariation(array $config) : string
    {
        $request = $this->requestStack->getCurrentRequest();
        $key = $config['cache_key'];

        if ($config['vary_path_info']) {
            $key .= Urlizer::urlize($request->getPathInfo()).'__';
        }

        if ($config['vary_query_string'] && $request->getQueryString()) {
            $key .= 'qs_'.Urlizer::urlize($request->getQueryString()).'__';
        }

        foreach (explode(',',$config['vary']) as $vary) {
            $vary = trim($vary);
            $key .= $vary.'_'.Urlizer::urlize($request->headers->get($vary)).'__';
        }

        return $key;
    }

    public function get(BlockTypeInterface $blockType, Block $block)
    {
        $config = $this->getCacheConfig($blockType, $block);

        $cache = $this->cacheItemPool->getItem($this->getCacheKeyWithVariation($config));

        if (!$cache->isHit()) {
            return false;
        }

        return $cache->get();
    }

    public function write(BlockTypeInterface $blockType, Block $block, $content)
    {
        $config = $this->getCacheConfig($blockType, $block);

        $cache = $this->cacheItemPool->getItem($this->getCacheKeyWithVariation($config));
        $cache->expiresAfter(\DateInterval::createFromDateString($config['expires_after'] ? $config['expires_after'] : '1 second'));
        $cache->tag($config['cache_key']);

        if ($content instanceof Response) { 
            if (!$content->isCacheable()) {
                return;
            }

            $content = $content->getContent();
        }

        $cache->set($content);

        $this->cacheItemPool->save($cache);
    }

    public function invalidateByTag(BlockTypeInterface $blockType, Block $block)
    {
        if (!$blockType instanceof CacheableBlockInterface) {
            return;
        }

        $config = $this->getCacheConfig($blockType, $block);

        $this->cacheItemPool->invalidateTags([ $config['cache_key'] ]);
    }

}
