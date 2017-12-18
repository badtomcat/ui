<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月7日
 * @Desc: 
 * 依赖:
 */
namespace Badtomcat\Ui\FormWrap;

use Badtomcat\Ui\Form;

abstract class Wrap {
	abstract public function wrap(Form $form);
}