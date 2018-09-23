<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Opera\CoreBundle\BlockType\BaseBlock;
use Opera\CoreBundle\Entity\Block;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class <?= $class_name ?> extends BaseBlock implements BlockTypeInterface, ContainerAwareInterface
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
}