<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\JQuery\Type;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Form\JQuery\Type\TextAutocompleterType;

/**
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 */
class TextAutocompleterTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create(new TextAutocompleterType());
        $view = $form->createView();

        $this->assertEquals(array(), $view->getvar('suggestions'));
        $this->assertEquals('', $form->getViewData());
        $this->assertEquals('', $view->getVar('value'));
        $this->assertNull($view->getVar('route_name'));
    }

    public function testValue()
    {
        $form = $this->factory->create(new TextAutocompleterType(), null, array(
            'suggestions' => array('Foo', 'Bar')
        ));

        $form->setData('Foo');
        $view = $form->createView();

        $this->assertEquals(array('Foo', 'Bar'), $view->getVar('suggestions'));
        $this->assertEquals('Foo', $form->getViewData());
        $this->assertEquals('Foo', $view->getVar('value'));
    }

    public function testValueWithAjax()
    {
        $form = $this->factory->create(new TextAutocompleterType(), null, array(
            'route_name' => 'genemu_choice'
        ));

        $form->setData('Foo');
        $view = $form->createView();
        $form->bind('Bar');

        $this->assertEquals(array(), $view->getVar('suggestions'));
        $this->assertEquals('Bar', $form->getViewData());

        $this->assertEquals('Bar', $form->getData());
    }

    public function testValueMultipleWithAjax()
    {
        $form = $this->factory->create(new TextAutocompleterType(), null, array(
            'route_name' => 'genemu_choice',
            'multiple' => true
        ));

        $form->setData(array('Foo', 'Bar'));
        $view = $form->createView();
        $form->bind(array('Foo', 'Ri'));

        $this->assertEquals(array(), $view->getVar('suggestions'));
        $this->assertEquals('Foo, Ri, ', $form->getViewData());

        $this->assertEquals(array('Foo', 'Ri'), $form->getData());
    }

    public function testValueMultiple()
    {
        $form = $this->factory->create(new TextAutocompleterType(), null, array(
            'suggestions' => array('Foo', 'Bar', 'Ri'),
            'multiple' => true
        ));

        $form->setData(array('Foo', 'Bar'));
        $view = $form->createView();
        $form->bind(array('Foo'));

        $this->assertEquals('Foo, ', $form->getViewData());

        $this->assertEquals(array('Foo'), $form->getData());

        $this->assertEquals(array('Foo', 'Bar', 'Ri'), $view->getVar('suggestions'));

        $this->assertEquals('Foo, Bar, ', $view->getVar('value'));
    }
}
