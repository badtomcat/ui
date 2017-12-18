<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace Badtomcat\Ui\Base\Input;

use Badtomcat\Ui\Base\FormInput;

class Datetime extends FormInput {
	public function __construct($name = "", $value = "") {
	    parent::__construct("input",array (
            "type" => "datetime",
            "value" => $value
        ));
        $this->isSelfClose = true;
		if ($name) {
			$this->setName ( $name );
		}
	}
}