<?php
/**
 * 给它一个COMPONENT,调用MATCH
 * 生成一个ELEMENT
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30
 * Time: 17:10
 */

namespace Badtomcat\Ui\Adapter\Mysql;


use Badtomcat\Data\Component;
use Badtomcat\Ui\Base\FormInput;
use Badtomcat\Ui\Base\Input\Button;
use Badtomcat\Ui\Base\Input\CheckboxGrp;
use Badtomcat\Ui\Base\Input\Date;
use Badtomcat\Ui\Base\Input\Datetime;
use Badtomcat\Ui\Base\Input\file;
use Badtomcat\Ui\Base\Input\Image;
use Badtomcat\Ui\Base\Input\Password;
use Badtomcat\Ui\Base\Input\RadioGrp;
use Badtomcat\Ui\Base\Input\reset;
use Badtomcat\Ui\Base\Input\Select;
use Badtomcat\Ui\Base\Input\Submit;
use Badtomcat\Ui\Base\Input\Text;
use Badtomcat\Ui\Base\Input\Textarea;

class FieldUI
{
    const TYPE_BUTTON = "button";
    const TYPE_CHECKBOX_GRP = "checkboxGrp";
    const TYPE_DATE = "date";
    const TYPE_DATETIME = "datetime";
    const TYPE_FILE = "file";
    const TYPE_IMAGE = "image";
    const TYPE_PASSWORD = "password";
    const TYPE_RADIO_GRP = "radioGrp";
    const TYPE_RESET = "reset";
    const TYPE_SELECT = "select";
    const TYPE_SUBMIT = "submit";
    const TYPE_TEXT = "text";
    const TYPE_TEXTAREA = "textarea";
    /**
     *
     * @var Component
     */
    public $component;

    /**
     *
     * @var FormInput
     */
    public $element;
    /**
     * 数据格式为:
     * 字段名 => element类型名
     * element类型名(\Badtomcat\ui\base\input类型)
     *
     * @var array
     */
    public $uiTypeMap = array();

    public function __construct(Component $component = NULL)
    {
        if (!is_null($component)) {
            $this->setComponent($component);
        }
    }

    public function setComponent(Component $component)
    {
        $this->component = $component;
        return $this;
    }

    /**
     * 对于类型为enum 或者 set时有用
     *
     * @param array $domain
     * @return $this
     */
    public function setDomain(array $domain)
    {
        $this->component->setDomain($domain);
        return $this;
    }

    /**
     * 可用的类型有:
     * button checkboxGrp date datetime
     * file image image password
     * radioGrp reset select submit
     * text textarea
     *
     * @param array $map
     * @return $this
     */
    public function setUiTypeMap(array $map)
    {
        $this->uiTypeMap = $map;
        return $this;
    }

    /**
     * 可用的类型有:
     * button checkboxGrp date datetime
     * file image image password
     * radioGrp reset select submit
     * text textarea
     *
     * @param string $field
     * @param string $type
     * @return $this
     */
    public function addUiTypeMap($field, $type)
    {
        $this->uiTypeMap [$field] = $type;
        return $this;
    }

    /**
     *
     * @param string $field
     * @return $this
     */
    public function rmUiTypeMap($field)
    {
        if (isset ($this->uiTypeMap [$field]))
            unset ($this->uiTypeMap [$field]);
        return $this;
    }

    /**
     * 调用match过后，成员element可用
     *
     * @return boolean
     */
    public function match()
    {
        $name = $this->component->getName();
        if (!array_key_exists($name, $this->uiTypeMap))
            return $this->defMatch();
        $elementType = $this->uiTypeMap [$name];

        switch ($elementType) {
            case "button" :
                $this->element = new Button ($this->component->getDefault());
                return true;
            case "checkboxGrp" :
                $this->element = new CheckboxGrp ($this->component->getName(), $this->component->getDomain(), $this->component->getDefault());
                return true;
            case "date" :
                $this->element = new Date ($this->component->getName(), $this->component->getDefault());
                return true;
            case "datetime" :
                $this->element = new Datetime ($this->component->getName(), $this->component->getDefault());
                return true;
            case "file" :
                $this->element = new File ($this->component->getName(), $this->component->getDefault());
                return true;
            case "image" :
                $this->element = new Image ($this->component->getDefault());
                return true;
            case "password" :
                $this->element = new Password ($this->component->getName(), $this->component->getDefault());
                return true;
            case "radioGrp" :
                $this->element = new RadioGrp ($this->component->getName(), $this->component->getDomain(), $this->component->getDefault());
                return true;
            case "reset" :
                $this->element = new Reset ($this->component->getDefault());
                return true;
            case "select" :
                $this->element = new Select ($this->component->getName(), $this->component->getDomain(), $this->component->getDefault());
                return true;
            case "submit" :
                $this->element = new Submit ($this->component->getDefault());
                return true;
            case "text" :
                $this->element = new Text ($this->component->getName(), $this->component->getDefault());
                return true;
            case "textarea" :
                $this->element = new Textarea ($this->component->getName(), $this->component->getDefault());
                return true;
        }
        return false;
    }

    /**
     *
     * @return boolean
     */
    private function defMatch()
    {
        switch ($this->component->getDataType()) {
            case "tinyint" :
            case "smallint" :
            case "int" :
            case "decimal" :
            case "mediumint" :
            case "float" :
            case "double" :
            case "tinyblob" :
            case "varchar" :
            case "char" :
            case "binary" :
            case "varbinary" :
            case "time" :
            case "year" :
                $this->element = new Text ($this->component->getName(), $this->component->getDefault());
                return true;
            case "tinytext" :
            case "blob" :
            case "mediumblob" :
            case "mediumtext" :
            case "longblob" :
            case "longtext" :
            case "text" :
                $this->element = new Textarea ($this->component->getName(), $this->component->getDefault());
                return true;
            case "datetime" :
            case "timestamp" :
                $this->element = new Datetime ($this->component->getName(), $this->component->getDefault());
                return true;
            case "date" :
                $this->element = new Date ($this->component->getName(), $this->component->getDefault());
                return true;
            case "enum" :
                $this->element = new Select ($this->component->getName(), $this->component->getDomain(), $this->component->getDefault());
                return true;
            case "set" :
                $this->element = new CheckboxGrp ($this->component->getName(), $this->component->getDomain(), $this->component->getDefault());
                return true;
        }
        return false;
    }
}