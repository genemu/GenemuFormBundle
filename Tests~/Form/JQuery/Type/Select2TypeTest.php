<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\JQuery\Type;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Form\JQuery\Type\Select2Type;

/**
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 */
class Select2TypeTest extends TypeTestCase
{
    public function testConstructorAffectsParentType()
    {
        foreach (array('country', 'choice', 'hidden') as $type) {
            $form = $this->factory->create(new Select2Type($type));

            $this->assertEquals(
                $type,
                $form->getConfig()->getType()->getParent()->getName()
            );
        }
    }

    public function testSelectSingle()
    {
        $form = $this->factory->create(new Select2Type('choice'), null, array(
            'choices' => array('foo' => 'Foo', 'bar' => 'Bar')
        ));

        $form->bind('foo');

        $this->assertEquals('foo', $form->getData());
    }

    public function testSelectMultiple()
    {
        $form = $this->factory->create(new Select2Type('choice'), null, array(
            'choices' => array('foo' => 'Foo', 'bar' => 'Bar'),
            'multiple' => true
        ));

        $form->bind(array('foo'));

        $this->assertEquals(array('foo'), $form->getData());
        $this->assertEquals(array('foo'), $form->getViewData());
    }

    public function testHiddenSingle()
    {
        $form = $this->factory->create(new Select2Type('hidden'));

        $form->bind('Touti');

        $this->assertEquals('Touti', $form->getData());
        $this->assertEquals('Touti', $form->getViewData());
    }

    public function testHiddenMultiple()
    {
        $form = $this->factory->create(new Select2Type('hidden'), null, array(
            'configs' => array('multiple' => true)
        ));

        $form->bind('Touti,Douti');

        $this->assertEquals(array('Touti', 'Douti'), $form->getData());
        $this->assertEquals('Touti,Douti', $form->getViewData());
    }

    public function testHiddenMultipleDefault()
    {
        $form = $this->factory->create(new Select2Type('hidden'), array('Touti', 'Douti'), array(
            'configs' => array('multiple' => true)
        ));

        $this->assertEquals(array('Touti', 'Douti'), $form->getData());
        $this->assertEquals('Touti,Douti', $form->getViewData());
    }
}
