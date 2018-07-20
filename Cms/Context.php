<?php

namespace Opera\CoreBundle\Cms;

class Context
{
    private $variables = [];

    public function setVariables(array $variables)
    {
        $this->variables = $variables;
    }

    public function toArray() : array
    {
        return $this->variables;
    }
}