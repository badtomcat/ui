<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月12日
 * @Desc: 
 * 依赖:
 */
namespace Badtomcat\Ui\paginationWrap;

use Badtomcat\Ui\Pagination;

abstract class paginationWrap {
	abstract public function wrap(Pagination $pagination);
}