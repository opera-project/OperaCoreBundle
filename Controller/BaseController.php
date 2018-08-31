<?php

namespace Opera\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class BaseController extends Controller
{   
    protected function renderPage(array $variables = array())
    {
        $page = $this->getRequest()->get('_opera_page');

        $variables = array_merge($variables, [
            '_opera_page' => $page,
        ]);
        $this->get(\Opera\CoreBundle\Cms\Context::class)->setVariables($variables);

        return $this->render(
            sprintf('layouts/%s.html.twig', $page->getLayout()),
            $variables
        );
    }

    protected function getRequest()
    {
        return $this->get('request_stack')->getCurrentRequest();
    }
}
