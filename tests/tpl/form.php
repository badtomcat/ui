<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18
 * Time: 13:30
 */
/**
 * @var \Badtomcat\Ui\Base\Form $form;
 */

/**
 * @var \Badtomcat\Ui\Base\Element $item
 */

$form->addSubmit("提交");
?>
<div class="abc">
    <?php foreach ($form as $item):?>
    <div class="row">
        <div class="left">
            <?php print $item->getName()?>
        </div>
        <div class="right">
            <?php print $item->dumpHtml()?>
        </div>
    </div>
    <?php endforeach;?>
</div>
