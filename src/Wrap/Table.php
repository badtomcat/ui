<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月7日
 * @Desc:
 * 依赖:
 */

namespace Badtomcat\Ui\Wrap;


use Badtomcat\Ui\Base\Element;
use Badtomcat\Ui\Base\Textnode;
use Badtomcat\Ui\FormWrap\TableWrap;

class Table extends TableWrap
{
    /**
     *
     * @var \Badtomcat\Ui\Table
     */
    public $tb;
    /**
     *
     * @var \Badtomcat\Ui\Base\Node
     */
    public $table;
    /**
     *
     * @var \Badtomcat\Ui\Base\Node
     */
    public $thead;
    /**
     *
     * @var \Badtomcat\Ui\Base\Node
     */
    public $tbody;
    /**
     *
     * @var \Badtomcat\Ui\Base\Node
     */
    public $tfoot;
    /**
     *
     * @var \Badtomcat\Ui\Base\Node
     */
    public $caption;

    public function __construct()
    {
    }

    /**
     *
     * @return number
     */
    public function getColspan()
    {
        return isset ($this->tb->data [0]) ? count($this->tb->data [0]) : 0;
    }

    public function wrap(\Badtomcat\Ui\Table $table)
    {
        $this->tb = $table;
        $this->table = new Element ("table");
        $this->caption = new Element ("caption");
        $this->thead = new Element ("thead");
        $tr = new Element ("tr");
        foreach ($this->tb->alias() as $field => $alias) {
            $th = new Element ("th");
            $th->appendNode(new Textnode ($alias));
            $tr->appendNode($th);
        }
        $this->thead->appendNode($tr);

        $this->tbody = new Element ("tbody");
        $this->tfoot = new Element ("tfoot");

        foreach ($this->tb->data as $key => $item) {
            $tr = new Element ("tr");
            // 这一层循环代替TD层循环
            foreach ($this->tb->alias() as $field => $alias) {
                $content = $alias;
                $td = new Element ("td");
                if (array_key_exists($field, $item)) {
                    $content = $item [$field];
                }

                if (array_key_exists($field, $this->tb->dataFilter) && is_callable($this->tb->dataFilter [$field])) {
                    $content = call_user_func_array($this->tb->dataFilter [$field], array(
                        $this,
                        $tr,
                        $td,
                        $item,
                        $content
                    ));
                }

                // domain
                if (array_key_exists($field, $this->tb->domain)) {
                    $ret = array();
                    $set = explode(",", $content);
                    foreach ($set as $s) {
                        if (array_key_exists($s, $this->tb->domain [$field])) {
                            $ret [] = $this->tb->domain [$field] [$s];
                        }
                    }
                    $content = join(",", $ret);
                }
                $td->appendNode(new textnode ($content));
                $tr->appendNode($td);
            }
            $this->tbody->appendNode($tr);
        }
        $this->table->appendNode($this->caption);
        $this->table->appendNode($this->thead);
        $this->table->appendNode($this->tbody);
        $this->table->appendNode($this->tfoot);
        $table->table = $this->table;
    }
}