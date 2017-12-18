<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace Badtomcat\Ui\Base\Input;

use Badtomcat\Ui\Base\FormInput;

class file extends FormInput {
	public function __construct($name = "", $value = "") {
        parent::__construct("input",array (
            "type" => "file",
            "value" => $value
        ));
        $this->isSelfClose = true;
		if ($name) {
			$this->setName ( $name );
		}
	}

    /**
     * @param $val
     * @return $this
     */
	public function setValue($val) {
		$this->setAttr ( "value", $val );
		return $this;
	}

    /**
     * @return $this
     */
	public function setRequire() {
		$this->setAttr ( "require" );
		return $this;
	}

    /**
     * @return $this
     */
	public function rmRequire() {
		$this->rmAttr ( "require" );
		return $this;
	}
}