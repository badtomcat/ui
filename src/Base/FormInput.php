<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月3日
 * @Desc: 
 * 依赖:
 */
namespace Badtomcat\Ui\Base;

class FormInput extends Element
{
	public $alias;
	public $name;
    public $isHidden = false;
    /**
     * @param string $name
     * @return Node
     */
	public function setName($name) {
		$this->name = $name;
		return parent::setName ( $name );
	}

    /**
     * @param string $ak
     * @param null $av
     * @return Node
     */
	public function setAttr($ak, $av = null) {
		if (strtolower ( $ak ) == "name") {
			$this->name = $av;
		}
		return parent::setAttr ( $ak, $av );
	}

    /**
     * @param $hide
     * @return $this
     */
	public function setHidden($hide)
    {
        $this->isHidden = $hide;
        return $this;
    }
}
