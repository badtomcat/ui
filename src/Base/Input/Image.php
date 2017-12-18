<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace Badtomcat\Ui\Base\Input;

use Badtomcat\Ui\Base\FormInput;

class Image extends FormInput {
	public function __construct($src = "") {
        parent::__construct("input",array (
            "type" => "image",
            "src" => $src
        ));
        $this->isSelfClose = true;
	}

    /**
     * @param $src
     * @return $this
     */
	public function setSrc($src) {
		$this->setAttr ( "src", $src );
		return $this;
	}
}