<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Opera\CoreBundle\BlockType\BaseBlock;
use Opera\CoreBundle\Entity\Block;

class <?= $class_name ?> extends BaseBlock implements BlockTypeInterface
{
    public function getType() : string
    {
        return '<?= $block ?>';
    }

    public function execute(Block $block) : array
    {    
        return [
            // Set here your template vars
        ];
    }
}