<?php

namespace Opera\CoreBundle\Controller;

class PageController extends BaseController
{
    public function index()
    {
        return $this->renderPage();
    }
}
