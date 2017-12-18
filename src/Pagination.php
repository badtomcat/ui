<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月9日
 * @Desc: 
 * 依赖:
 */
namespace Badtomcat\Ui;

use Badtomcat\Ui\Base\Element;
use Badtomcat\Ui\paginationWrap\paginationWrap;

class Pagination {
	public $pagination;
	/**
	 *
	 * @var Element
	 */
	public $select;
	/**
	 *
	 * @var Element
	 */
	public $btnGrp;
	/**
	 *
	 * @var Element
	 */
	public $btnPriv;
	/**
	 *
	 * @var Element
	 */
	public $btnNext;
	public $selectEnabled = true;
	public function __construct(\Badtomcat\Data\Pagination $pagination) {
		$this->pagination = $pagination;
		$this->btnGrp = new Element ( "" );
		$this->btnPriv = null;
		$this->btnNext = null;
		$this->select = null;
	}
	public function setSelectEnabled($f) {
		$this->selectEnabled = $f;
		return $this;
	}
	public function wrap(PaginationWrap $wrap) {
		$wrap->wrap ( $this );
		return $this;
	}
}