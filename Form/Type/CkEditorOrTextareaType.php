<?php

namespace Opera\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class CkEditorOrTextareaType extends AbstractType
{
    public function getParent()
    {
        if (class_exists('FOS\CKEditorBundle\Form\Type\CKEditorType')) {
            return \FOS\CKEditorBundle\Form\Type\CKEditorType::class;
        }
        
        return \Symfony\Component\Form\Extension\Core\Type\TextareaType::class;
    }
}
