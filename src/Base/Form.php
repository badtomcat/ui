<?php

/**
 * ELEMENT有NAME属性,可以用NAME访问
 * @Author: awei.tian
 * @Date: 2016年8月5日
 * @Desc: 
 * 依赖:
 */
namespace Badtomcat\Ui\Base;



use Badtomcat\Ui\Base\Input\Submit;

class Form extends Element {
	public $method = "get";
	public $action = "";
	public $isUpload = false;
	public function __construct() {
		parent::__construct("form");
	}

    /**
     * @param string $text
     * @return $this
     */
	public function addSubmit($text="submit")
    {
        $this->appendNode(new Submit($text));
        return $this;
    }

    public function appendNode(Node $node)
    {
        if ($node instanceof Element)
        {
            if ($node->hasAttr("name"))
            {
                $this->childNodes[$node->getAttr("name")] = $node;
                return $this;
            }
        }
        return parent::appendNode($node); // TODO: Change the autogenerated stub
    }

    /**
     * @param $name
     * @return Element
     */
    public function get($name)
    {
        if (isset($this->childNodes[$name]))
        {
            if ($this->childNodes[$name] instanceof Element)
                return $this->childNodes[$name];
        }
        return null;
    }

    /**
	 *
	 * @return $this
	 */
	public function setUploadForm() {
		$this->setAttr ( "enctype", "multipart/form-data" );
		return $this;
	}
	/**
	 * _blank,_self,_parent,_top,framename
	 *
	 * @param string $tar        	
	 * @return $this
	 */
	public function setTarget($tar = "_blank") {
		$this->setAttr ( "target", $tar );
		return $this;
	}
	
	/**
	 * application/x-www-form-urlencoded,multipart/form-data,text/plain
	 *
	 * @param string $enc        	
	 * @return $this
	 */
	public function setEnctype($enc = "application/x-www-form-urlencoded") {
		$this->setAttr ( "enctype", $enc );
		return $this;
	}
	/**
	 * UTF-8,ISO-8859-1,gb2312
	 *
	 * @param string $cs        	
	 * @return $this
	 */
	public function setCharset($cs = "UTF-8") {
		$this->setAttr ( "accept-charset", $cs );
		return $this;
	}
	/**
	 *
	 * @param string $act
	 * @return $this
	 */
	public function setAction($act) {
		$this->setAttr ( "action", $act );
		return $this;
	}
	/**
	 *
	 * @param string $m        	
	 * @return $this
	 */
	public function setMethod($m) {
		$this->setAttr ( "method", $m );
		return $this;
	}
}