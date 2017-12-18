<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Badtomcat\Db\Connection\MysqlPdoConn;
use Badtomcat\Ui\Form;

class UiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MysqlPdoConn
     */
    private $con;
    public function setUp()
    {
        $this->con = new MysqlPdoConn([
            'host' => "127.0.0.1",
            'port' => "3306",
            'database' => "tpv3",
            'user' => "root",
            'password' => "root",
            'charset' => "utf8"
        ]);
    }
    public function testForm()
    {
        //这个地方有个BUG,不知道是PHPUNITTEST,还是什么原因,注释掉这行
        //有错误:Fatal error: Cannot use Badtomcat\Data\Tuple as Tuple because the name is already in use in E:\badtomcat\ui\src\Form.php on line 40

        new Form();
    }

    /**
     * @throws Exception
     */
    public function testConstructor()
    {
        $tuple = new \Badtomcat\Data\Mysql\Table2Tuple($this->con);
        $tuple->setTbName("game");
        $tuple->initTuple();
        $tuple = $tuple->tuple;

        $override = \Badtomcat\Data\Mysql\Importer::importTuple(__DIR__."/meta");


        $tuple->rewrite($override);
        $tuple->get("game_id")->setDefault("bar");
        $tuple->get("creator")->setDefault("1");
//        var_dump($tuple);
        //这个值被META中文件重写
        $this->assertEquals($tuple->get('creator')->getDataType(),'enum');
        $this->assertEquals($tuple->get('cover')->getDomain(),250);
        $this->assertEquals($tuple->get('force')->getDomain(),2);
        $form = new Form($tuple);
        $form->initFormElement();
        $this->assertEquals('<form><input type="text" value name="introduce"/><input type="text" value name="game_name"/><input type="text" value="bar" name="game_id"/><input type="text" value name="order_index"/><input type="text" value name="cover"/><input type="text" value="1" name="status"/><input type="datetime" value="CURRENT_TIMESTAMP" name="create_time"/><select name="creator"><option value="0">1</option><option value="1" selected>3</option><option value="2">5</option><option value="3">7</option><option value="4">8</option></select><input type="text" value name="force"/></form>',$form->getFormElement()->dumpHtml()) ;

    }

    public function testSelect()
    {
        $select = new \Badtomcat\Ui\Base\Input\Select("foo",[
            "aaa","bbb","ccc"
        ],"bbb");
        $this->assertEquals('<select name="foo"><option value="0" selected>aaa</option><option value="1">bbb</option><option value="2">ccc</option></select>',$select->dumpHtml());
    }

    public function testSetUitype()
    {
        $tuple = new \Badtomcat\Ui\Tuple();
        $field = new \Badtomcat\Data\Mysql\Field();
        $field->setName("foo");
        $field->setDataType_enum();
        $field->setDomain(['111','222']);
        $field->setDefault('222');
        $tuple->append($field);

        $field = new \Badtomcat\Data\Mysql\Field();
        $field->setName("bar");
        $field->setDataType_enum();
        $field->setDomain(['111','222']);
        $field->setDefault('222');
        $tuple->append($field);

        $form = new Form();
        $form->addUiType('bar',\Badtomcat\Ui\Adapter\Mysql\FieldUI::TYPE_TEXT);
        $form->setTuple($tuple)->initFormElement();
        $form->getFormElement()->setMethod('POST')->setAction('/foo/bar');
        $this->assertEquals('<form method="POST" action="/foo/bar"><select name="foo"><option value="0">111</option><option value="1">222</option></select><input type="text" value="222" name="bar"/></form>',$form->getFormElement()->dumpHtml());
        $this->assertArraySubset(['foo','bar'],$form->tuple->getOrderIndex());
    }

    public function testWrap()
    {
        $tuple = new \Badtomcat\Ui\Tuple();
        $field = new \Badtomcat\Data\Mysql\Field();
        $field->setName("foo");
        $field->setDataType_enum();
        $field->setDomain(['111','222']);
        $field->setDefault('222');
        $tuple->append($field);

        $field = new \Badtomcat\Data\Mysql\Field();
        $field->setName("bar");
        $field->setDataType_enum();
        $field->setDomain(['111','222']);
        $field->setDefault('222');
        $tuple->append($field);

        $form = new Badtomcat\Ui\Form($tuple);
        $form->globalClass = 'control';
        $form->class = [
            'foo' => 'foocls1 foocls2'
        ];
        $form->addUiType('bar',\Badtomcat\Ui\Adapter\Mysql\FieldUI::TYPE_TEXT);
        $form->initFormElement();
        $form->getFormElement()->setMethod('POST')->setAction('/foo/bar');


        /**
         * @var \Badtomcat\Ui\Base\Element $item
         */
        $this->wrapForm($form->getFormElement());

    }
    private function wrapForm(\Badtomcat\Ui\Base\Form $form)
    {
        ob_start();
        include __DIR__."/tpl/form.php";
        $ret = ob_get_clean();
//        file_put_contents(__DIR__."/tpl/form.out.txt",$ret);exit;
        $con = file_get_contents(__DIR__."/tpl/form.out.txt");
        $this->assertEquals($con,$ret);
    }
}
