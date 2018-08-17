<?php

namespace Opera\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ManagerRegistry;

class ModelToIdsTransformer implements DataTransformerInterface
{
    private $class;

    private $registry;

    public function __construct(ManagerRegistry $registry, string $class)
    {
        $this->registry = $registry;
        $this->class = $class;
    }

    public function transform($ids)
    {
        return $this->registry
                    ->getRepository($this->class)
                    ->findById($ids);
    }

    public function reverseTransform($entities)
    {
        return array_map(function ($item) {
            return (string) $item->getId();
        }, is_array($entities) ? $entities : $entities->toArray());
    }

}