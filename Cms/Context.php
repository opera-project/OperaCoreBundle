<?php

namespace Opera\CoreBundle\Cms;

use Symfony\Component\HttpFoundation\Response;

class Context
{
    private $variables = [];

    private $responses = [];

    public function setVariables(array $variables)
    {
        $this->variables = $variables;
    }

    public function toArray() : array
    {
        return $this->variables;
    }

    public function addResponse(Response $response)
    {
        $this->responses[] = $response;
    }

    public function getResponses() : array
    {
        return $this->responses;
    }
}