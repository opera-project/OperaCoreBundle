<?php

namespace Opera\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Opera\CoreBundle\Cms\Context;

class ResponseListener implements EventSubscriberInterface
{
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        foreach ($this->context->getResponses() as $response) {
            if ($response->isRedirection()) {
                $event->setResponse($response);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array(
                'onKernelResponse',
            ),
        );
    }
}