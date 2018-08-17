<?php

namespace Opera\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Opera\CoreBundle\Form\DataTransformer\ModelToIdsTransformer;
use Opera\CoreBundle\Form\DataTransformer\ModelToIdTransformer;

class OperaAdminAutocompleteType extends AbstractType
{
    private $configManager;
    
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    public function __construct(ManagerRegistry $registry, ConfigManager $configManager)
    {
        $this->registry = $registry;
        $this->configManager = $configManager;
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function getBlockPrefix()
    {
        return 'opera_autocomplete';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (null === $config = $this->configManager->getEntityConfigByClass($options['class'])) {
            throw new \InvalidArgumentException(sprintf('The configuration of the "%s" entity is not available (this entity is used as the target of the "%s" autocomplete field).', $options['class'], $form->getName()));
        }

        $view->vars['autocomplete_entity_name'] = $config['name'];
    }
   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        if ($options['multiple']) {
            $builder->addModelTransformer(new ModelToIdsTransformer($this->registry, $options['class']));
            return;
        }

        $builder->addModelTransformer(new ModelToIdTransformer($this->registry, $options['class']));

    }
}