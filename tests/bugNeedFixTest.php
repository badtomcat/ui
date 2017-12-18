<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Badtomcat\Ui\Form;

class bugNeedFixTest extends \PHPUnit_Framework_TestCase
{
    public function testFormWrap()
    {

        //may be found at https://bugs.php.net/bug.php?id=66773
        $tuple = new \Badtomcat\Ui\Tuple();
        $field = new \Badtomcat\Data\Mysql\Field();
        $field->setName("foo");
        $field->setDataType_enum();
        $field->setDomain(['111','222']);
        $field->setDefault('222');
        $tuple->append($field);

        $form = new Form();
        $form->addUiType('bar',\Badtomcat\Ui\Adapter\Mysql\FieldUI::TYPE_TEXT);
        $form->initFormElement();
        $form->getFormElement()->setMethod('POST')->setAction('/foo/bar');



    }
}
