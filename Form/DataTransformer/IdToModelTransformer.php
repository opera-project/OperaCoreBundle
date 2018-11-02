<?php

namespace Opera\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ManagerRegistry;

class IdToModelTransformer implements DataTransformerInterface
{
    private $class;

    private $registry;

    public function __construct(ManagerRegistry $registry, string $class)
    {
        $this->registry = $registry;
        $this->class = $class;
    }

    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        return $this->registry
                    ->getRepository($this->class)
                    ->find($id);
    }

    public function transform($entity)
    {
        if (!$entity) {
            return null;
        }

        return $entity->getId();
    }

}