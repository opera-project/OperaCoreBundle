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

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->attributes->has('_controller')) {
            // routing is already done
            return;
        }

        $pathInfo = rtrim(ltrim($request->getPathInfo(), '/'), '/');
        $page = $this->pageRepository->findOnePublishedWithoutRouteAndSlug($pathInfo);

        if (!$page) {
            return;
        }

        $request->attributes->set('_opera_page' , $page);
        $request->attributes->set('_route' , '_opera_page');      
        $request->attributes->set('_route_params', [
            '_opera_page_path' => $pathInfo,
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
            $page = $this->pageRepository->findOnePublishedWithPatternMatch($request->getPathInfo());

            if (!$page) {
                return;
            }

            $request->attributes->set('_route_params', RoutingUtils::getRouteVariables($page->getSlug(), $request->getPathInfo()));
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
