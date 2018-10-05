<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Opera\CoreBundle\BlockType\BaseBlock;
use Opera\CoreBundle\Entity\Block;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Opera\CoreBundle\BlockType\CacheableBlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $class_name ?> extends BaseBlock implements BlockTypeInterface, ContainerAwareInterface, CacheableBlockInterface
{
    use ControllerTrait;
    use ContainerAwareTrait;

    public function getType() : string
    {
        return '<?= $block ?>';
    }

    public function execute(Block $block) : array
    {    
        // Do here your controller
        // eg $form = $this->createForm(MyType::class);

        return [
            // Set here your template vars
        ];
    }

    public function getCacheConfig(OptionsResolver $resolver, Block $block)
    {
        $resolver->setDefaults([
            // Set your configs for cache
            // 'vary' => 'cookie',
        ]);
    }
}