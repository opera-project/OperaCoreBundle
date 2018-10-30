<?php

namespace Opera\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Opera\CoreBundle\Repository\PageRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use Opera\CoreBundle\Routing\RoutingUtils;

class RouterListener implements EventSubscriberInterface
{
    private $pageRepository;

    private $routePrefix;

    public function __construct(PageRepository $pageRepository, string $routePrefix)
    {
        $this->pageRepository = $pageRepository;
        $this->routePrefix    = $routePrefix;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->attributes->has('_controller')) {
            // routing is already done
            return;
        }

        $pathInfo = preg_replace('#^'.addslashes($this->routePrefix).'#', '', $request->getPathInfo());
        $pathInfo = rtrim(ltrim($pathInfo, '/'), '/');
        $page = $this->pageRepository->findOnePublishedWithoutRouteAndSlug($pathInfo);

        if (!$page) {
            return;
        }

        $request->attributes->set('_opera_page' , $page);
        $request->attributes->set('_route' , '_opera_page');      
        $request->attributes->set('_route_params', [
            '_opera_page_path' => urldecode($pathInfo),
        ]);  
        $request->attributes->set('_controller' , 'Opera\CoreBundle\Controller\PageController::index');
    }

    public function onKernelRequestForRoutes(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->attributes->has('_opera_page')) {
            // routing is already done
            return;
        }

        if (!$request->attributes->get('_route')) {
            return;
        }

        if ($request->attributes->get('_route') == '_opera_page') {
            $pathInfo = '/'.preg_replace('#^'.addslashes($this->routePrefix).'#', '', $request->getPathInfo());
            $page = $this->pageRepository->findOnePublishedWithPatternMatch($pathInfo);

            if (!$page) {
                return;
            }

            $routeVariables = RoutingUtils::getRouteVariables($page->getSlug(), $pathInfo, $page->getRequirements() ?? []);

            foreach ($routeVariables as $key => $value) {
                $request->attributes->set($key, $value);
            }

            $pathInfo = rtrim(ltrim($pathInfo, '/'), '/');
            $request->attributes->set('_route_params', [
                '_opera_page_path' => urldecode($pathInfo),
            ]);
            $request->attributes->set('_opera_page' , $page);
            $request->attributes->set('_controller' , 'Opera\CoreBundle\Controller\PageController::index');
            return;
        }

        $page = $this->pageRepository->findOnePublishedWithRoute($request->attributes->get('_route'));

        if (!$page) {
            return;
        }

        $request->attributes->set('_opera_page' , $page);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(
                array('onKernelRequest', 33),
                array('onKernelRequestForRoutes', 31)
            ),
        );
    }
}
