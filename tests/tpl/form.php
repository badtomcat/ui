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
$form->get("foo")->addClass("foo");
$form->addSubmit("提交");
?>
<div class="abc">
    <?php foreach ($form as $item):?>
    <div class="row">
        <?php if ($item instanceof \Badtomcat\Ui\Base\Input\Submit):?>
        <?php print $item->dumpHtml()?>
        <?php else :?>
        <div class="left"><?php print $item->getName().$item->tagName?></div>
        <div class="right"><?php print $item->dumpHtml()?></div>
        <?php endif;?>
    </div>
    <?php endforeach;?>
</div>
