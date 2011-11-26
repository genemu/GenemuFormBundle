<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Type\JQuery;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AutocompleterTypeTest extends TypeTestCase
{
    const CHOICELIST_CLASS = 'Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList';

    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter');
        $view = $form->createView();

        $this->assertInstanceOf(self::CHOICELIST_CLASS, $form->getAttribute('choice_list'));
        $this->assertEquals(array(), $form->getAttribute('choice_list')->getChoices());
        $this->assertEquals('', $form->getClientData());
        $this->assertEquals('', $view->get('value'));
        $this->assertEquals('', $view->get('autocompleter_value'));
        $this->assertNull($view->get('route_name'));
    }

    public function testValue()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter', null, array(
            'choices' => array('foo' => 'Foo', 'bar' => 'Bar')
        ));

        $form->setData('foo');
        $view = $form->createView();

        $this->assertEquals(array(
            array('value' => 'foo', 'label' => 'Foo'),
            array('value' => 'bar', 'label' => 'Bar')
        ), $form->getAttribute('choice_list')->getChoices());

        $this->assertFalse($form->getAttribute('multiple'));

        $this->assertEquals(json_encode(array(
            'value' => 'foo',
            'label' => 'Foo'
        )), $form->getClientData());
        $this->assertEquals(json_encode(array(
            'value' => 'foo',
            'label' => 'Foo'
        )), $view->get('value'));
        $this->assertEquals('Foo', $view->get('autocompleter_value'));
    }

    public function testValueWithAjax()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter', null, array(
            'route_name' => 'genemu_choice'
        ));

        $form->setData('foo');
        $view = $form->createView();
        $form->bind(json_encode(array(
            'label' => 'bar',
            'value' => 'bar'
        )));

        $this->assertEquals(array(), $form->getAttribute('choice_list')->getChoices());
        $this->assertEquals(json_encode(array(
            'value' => 0,
            'label' => 'foo',
        )), $view->get('value'));

        $this->assertEquals('foo', $view->get('autocompleter_value'));

        $this->assertEquals('bar', $form->getData());
        $this->assertEquals(json_encode(array(
            'value' => 0,
            'label' => 'bar',
        )), $form->getClientData());
    }

    public function testValueMultipleWithAjax()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter', null, array(
            'route_name' => 'genemu_choice',
            'multiple' => true
        ));

        $form->setData(array('foo' => 'Foo', 'bar' => 'Bar'));
        $view = $form->createView();
        $form->bind(json_encode(array(
            array('label' => 'Foo', 'value' => 'foo'),
            array('label' => 'Ri', 'value' => 'ri')
        )));

        $this->assertEquals(array(), $form->getAttribute('choice_list')->getChoices());
        $this->assertEquals(json_encode(array(
            array('value' => 'foo', 'label' => 'Foo'),
            array('value' => 'ri', 'label' => 'Ri'),
        )), $form->getClientData());

        $this->assertEquals(array('foo' => 'Foo', 'ri' => 'Ri'), $form->getData());

        $this->assertEquals(json_encode(array(
            array('value' => 'foo', 'label' => 'Foo'),
            array('value' => 'bar', 'label' => 'Bar'),
        )), $view->get('value'));
        $this->assertEquals('Foo, Bar, ', $view->get('autocompleter_value'));
    }

    public function testValueMultiple()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter', null, array(
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
        ), $form->getAttribute('choice_list')->getChoices());

        $this->assertEquals(json_encode(array(
            array('value' => 'foo', 'label' => 'Foo'),
            array('value' => 'bar', 'label' => 'Bar')
        )), $view->get('value'));

        $this->assertEquals('Foo, Bar, ', $view->get('autocompleter_value'));
    }
}
