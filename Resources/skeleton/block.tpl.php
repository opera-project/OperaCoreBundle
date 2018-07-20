<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use Opera\CoreBundle\BlockType\BlockTypeInterface;
use Opera\CoreBundle\BlockType\BaseBlock;

class <?= $class_name ?> extends BaseBlock implements BlockTypeInterface
{
    public function getType() : string
    {
        return '<?= $block ?>';
    }
}