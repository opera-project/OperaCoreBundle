<?php

namespace Opera\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ManagerRegistry;

class ModelToIdTransformer implements DataTransformerInterface
{
    private $class;

    private $registry;

    public function __construct(ManagerRegistry $registry, string $class)
    {
        $this->registry = $registry;
        $this->class = $class;
    }

    public function transform($id)
    {
        return $this->registry
                    ->getRepository($this->class)
                    ->find($id);
    }

    public function reverseTransform($entity)
    {
        return $entity->getId();
    }

}