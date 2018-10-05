<?php

namespace Opera\CoreBundle\Event;

use Opera\CoreBundle\Entity\Block;
use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Symfony\Component\HttpFoundation\Response;

class BlockPerRenderEvent extends BlockEvent
{
    private $response;

    public function isRendered() : bool
    {
        return !is_null($this->response);
    }

    public function getResponse() : Response
    {
        return $this->response;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

}