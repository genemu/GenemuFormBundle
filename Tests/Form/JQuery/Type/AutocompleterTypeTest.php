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
use Genemu\Bundle\FormBundle\Form\JQuery\Type\AutocompleterType;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AutocompleterTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create(new AutocompleterType('choice'));
        $view = $form->createView();

        $this->assertEquals(array(), $view->getvar('choices'));
        $this->assertEquals('', $form->getViewData());
        $this->assertEquals('', $view->getVar('value'));
        $this->assertEquals('', $view->getVar('autocompleter_value'));
        $this->assertNull($view->getVar('route_name'));
    }

    public function testValue()
    {
        $form = $this->factory->create(new AutocompleterType('choice'), null, array(
            'choices' => array('foo' => 'Foo', 'bar' => 'Bar')
        ));

        $form->setData('foo');
        $view = $form->createView();

        $this->assertEquals(array(
            array('value' => 'foo', 'label' => 'Foo'),
            array('value' => 'bar', 'label' => 'Bar')
        ), $view->getVar('choices'));

        $this->assertEquals(json_encode(array(
            'value' => 'foo',
            'label' => 'Foo'
        )), $form->getViewData());
        $this->assertEquals(json_encode(array(
            'value' => 'foo',
            'label' => 'Foo'
        )), $view->getVar('value'));
        $this->assertEquals('Foo', $view->getVar('autocompleter_value'));
    }

    public function testValueWithAjax()
    {
        $form = $this->factory->create(new AutocompleterType('choice'), null, array(
            'route_name' => 'genemu_choice'
        ));

        $form->setData('foo');
        $view = $form->createView();
        $form->bind(json_encode(array(
            'label' => 'bar',
            'value' => 'bar'
        )));

        $this->assertEquals(array(), $view->getVar('choices'));
        $this->assertEquals(json_encode(array(
            'value' => 'bar',
            'label' => 'bar',
        )), $form->getViewData());

        $this->assertEquals('bar', $form->getData());
        $this->assertEquals(json_encode(array(
            'value' => 'bar',
            'label' => 'bar',
        )), $form->getClientData());
    }

    public function testValueMultipleWithAjax()
    {
        $form = $this->factory->create(new AutocompleterType('choice'), null, array(
            'route_name' => 'genemu_choice',
            'multiple' => true
        ));

        $form->setData(array('foo' => 'Foo', 'bar' => 'Bar'));
        $view = $form->createView();
        $form->bind(json_encode(array(
            array('label' => 'Foo', 'value' => 'foo'),
            array('label' => 'Ri', 'value' => 'ri')
        )));

        $this->assertEquals(array(), $view->getVar('choices'));
        $this->assertEquals(json_encode(array(
            array('value' => 'foo', 'label' => 'Foo'),
            array('value' => 'ri', 'label' => 'Ri'),
        )), $form->getViewData());

        $this->assertEquals(array('foo', 'ri'), $form->getData());
    }

    public function testValueMultiple()
    {
        $form = $this->factory->create(new AutocompleterType('choice'), null, array(
            'choices' => array('foo' => 'Foo', 'bar' => 'Bar', 'ri' => 'Ri'),
            'multiple' => true
        ));

        $form->setData(array('foo', 'bar'));
        $view = $form->createView();
        $form->bind(json_encode(array(
            array('label' => 'Foo', 'value' => 'foo')
        )));

        $this->assertEquals(json_encode(array(
            array('value' => 'foo', 'label' => 'Foo'),
        )), $form->getClientData());

        $this->assertEquals(array('foo'), $form->getData());

        $this->assertEquals(array(
            array('label' => 'Foo', 'value' => 'foo'),
            array('label' => 'Bar', 'value' => 'bar'),
            array('label' => 'Ri', 'value' => 'ri'),
        ), $view->getVar('choices'));

        $this->assertEquals(json_encode(array(
            array('value' => 'foo', 'label' => 'Foo'),
            array('value' => 'bar', 'label' => 'Bar')
        )), $view->getVar('value'));

        $this->assertEquals('Foo, Bar, ', $view->getVar('autocompleter_value'));
    }
}
