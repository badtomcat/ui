<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月5日
 * @Desc:
 *        tuple是component的容器
 *        \TIAN\DATA\FORM类为每个component设置合适的ELEMENT
 *
 *        数据和包装器
 * ===========================================================
 *        alias
 *            formname => alias
 *        defaultValue
 *            formname => value
 *        dataFilter
 *            formname => filter(callback)
 *        uiType
 *            formname => type
 *        domain
 *            formname => domain
 *        nameMap
 *            componentname => formname
 * ============================================================
 *
 * FORM类需要TUPLE类来设置数据
 * WRAP类型来生成UI
 * TUPLE类由COMPONENT类型组成，实现了COMPONENT类的有\TIAN\MYSQL\FIELD
 *        FIELD类有NAME,DATATYPE属性，其中DATATYPE属性为MYSQL列属性
 *        NAME有 【列名】 和【表名.列名】 两种(有时需要多表同时操作)
 * \TIAN\MYSQL\FIELDUI类型把\TIAN\MYSQL\FIELD变成\Badtomcat\Ui\ELEMENT
 * TB2TUPLE是MYSQL表名到TUPLE的转换类
 *        MYSQL表名到FIELD
 * 依赖:
 */

namespace Badtomcat\Ui;

use Badtomcat\Data\Component;
use Badtomcat\Data\Tuple;
use Badtomcat\Ui\Adapter\Mysql\FieldUI;
use Badtomcat\Ui\Base\Node;
use Badtomcat\Ui\Base\Form as BaseForm;

class Form
{
    /**
     *
     * @var Tuple
     */
    public $tuple;
    /**
     *
     * @var BaseForm
     */
    protected $form;
    protected $children = array();
    public $default = array();
    public $domain = array();
    /**
     * 默认ENUM使用SELECT,如果想改变这种默认行为
     * 定义这个数组,指定NAME到TYPE的KEY VALUE
     * 其中可用类型定义在FieldUI
     * @var array
     */
    public $uiType = array();
    /**
     * component的NAME到FORM的NAME的映射
     *
     * @var array
     */
    public $nameMap = array();
    /**
     * name => alias
     * 表单字段的先后顺序为此为准
     *
     * @var array
     */
    protected $alias = array();
    public $dataFilter = array();

    public function __construct(Tuple $tuple = null)
    {
        $this->form = new BaseForm();
        if (!is_null($tuple))
            $this->setTuple($tuple);
    }

    /**
     *
     * @param array $filter
     * @return $this
     */
    public function setDataFilter(array $filter)
    {
        $this->dataFilter = $filter;
        return $this;
    }

    /**
     *
     * @param array $def
     * @return $this
     */
    public function setDefaultValue(array $def)
    {
        $this->default = $def;
        return $this;
    }

    /**
     *
     * @param array $domain
     * @return $this
     */
    public function setDomain(array $domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     *
     * @param array $map
     * @return $this
     */
    public function setNameMap(array $map)
    {
        $this->nameMap = $map;
        return $this;
    }

    /**
     *
     * @param Tuple $tuple
     * @return $this
     */
    public function setTuple(Tuple $tuple)
    {
        $this->tuple = $tuple;
        return $this;
    }

    /**
     *
     * @param string $formName
     * @param string $alias
     * @return $this
     */
    public function setAlias($formName, $alias)
    {
        $this->alias [$formName] = $alias;
        return $this;
    }

    /**
     *
     * @param string $formName
     * @return $this
     */
    public function rmAlias($formName)
    {
        if (isset ($this->alias [$formName])) {
            unset ($this->alias [$formName]);
        }
        return $this;
    }

    /**
     *
     * @param array $alias
     * @return $this
     */
    public function mergeAlias(array $alias)
    {
        $this->alias = array_merge($this->alias, $alias);
        return $this;
    }

    /**
     *
     * @param string $formName
     * @param string $def
     * @return string
     */
    public function getAlias($formName, $def = "")
    {
        return array_key_exists($formName, $this->alias) ? $this->alias [$formName] : $def;
    }

    /**
     *
     * @return array:
     */
    public function alias()
    {
        return $this->alias;
    }

    /**
     * 可用的类型有:
     * button checkboxGrp date datetime
     * file image image password
     * radioGrp reset select submit
     * text textarea
     *
     * @param array $type
     * @return $this
     */
    public function setUiType(array $type)
    {
        $this->uiType = $type;
        return $this;
    }

    /**
     * @param $name
     * @param $type
     * @return $this
     */
    public function addUiType($name, $type)
    {
        $this->uiType[$name] = $type;
        return $this;
    }

    /**
     *
     * @param Node $node
     * @return $this
     */
    public function appendNode(Node $node)
    {
        $this->form->appendNode($node);
        return $this;
    }

    /**
     *
     * @return BaseForm
     */
    public function getFormElement()
    {
        return $this->form;
    }

    /**
     * 使用定义的DOMAIN,DEFAULT,ALIAS重写TUPLE
     * @param bool $order_tuple
     * @return $this
     */
    public function initFormElement($order_tuple = true)
    {
        if (is_null($this->tuple))
            return $this;
        $ui = new FieldUI();
        //把FORM的MAP设置到FIELDUI中
        $ui->setUiTypeMap($this->uiType);
        /**
         * @var Component $component
         */
        foreach (($order_tuple ? $this->tuple->order() : $this->tuple) as $component) {
            $formName = array_key_exists($component->getName(), $this->nameMap) ? $this->nameMap [$component->getName()] : $component->getName();
//            echo "\n===============$formName=============\n";
            if (array_key_exists($formName, $this->default)) {
                $component->setDefault($this->default [$formName]);
            }
            if (array_key_exists($formName, $this->dataFilter)) {
                if (is_callable($this->dataFilter [$formName])) {
                    $component->setDefault(call_user_func_array($this->dataFilter [$formName], array(
                        $this,
                        $this->tuple,
                        $component->getDefault()
                    )));
                }
            }
            if (array_key_exists($formName, $this->domain)) {
                $component->setDomain($this->domain [$formName]);
            }

            if ($ui->setComponent($component)->match()) {
                $this->appendNode($ui->element);
                if (array_key_exists($formName, $this->alias)) {
                    $component->setAlias($this->alias [$formName]);
                }
                $this->setAlias($formName, $component->getAlias());

                $ui->element->alias = $component->getAlias();
                $ui->element->setName($formName);
            }
        }
        return $this;
    }
}