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
use Genemu\Bundle\FormBundle\Form\JQuery\Type\AutocompleterType;
use Symfony\Component\Form\Extension\Core\View\ChoiceView;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AutocompleterTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create(new AutocompleterType('choice'));
        $view = $form->createView();

        $this->assertEquals(array(), $view->vars['choices']);
        $this->assertEquals('', $form->getViewData());
        $this->assertEquals('', $view->vars['value']);
        $this->assertEquals('', $view->vars['autocompleter_value']);
        $this->assertNull($view->vars['route_name']);
    }

    public function testEmptySimpleValue()
    {
        $form = $this->factory->create(new AutocompleterType('choice'), null, array(
            'choices' => array()
        ));
        $form->bind('');
        $this->assertEquals(null, $form->getData());
    }

    public function testEmptyArrayValue()
    {
        $form = $this->factory->create(new AutocompleterType('choice'), null, array(
            'choices' => array(),
            'multiple' => true
        ));
        $form->bind(array());
        $this->assertEquals(array(), $form->getData());
    }

    public function testValue()
    {
        $form = $this->factory->create(new AutocompleterType('choice'), null, array(
            'choices' => array('foo' => 'Foo', 'bar' => 'Bar')
        ));

        $form->setData('foo');
        $view = $form->createView();

        $this->assertEquals(array(
            new ChoiceView('foo', 'foo', 'Foo'),
            new ChoiceView('bar', 'bar', 'Bar'),
        ), $view->vars['choices']);

        $this->assertEquals(json_encode(array(
            'value' => 'foo',
            'label' => 'Foo'
        )), $form->getViewData());
        $this->assertEquals(json_encode(array(
            'value' => 'foo',
            'label' => 'Foo'
        )), $view->vars['value']);
        $this->assertEquals('Foo', $view->vars['autocompleter_value']);
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

        $this->assertEquals(array(), $view->vars['choices']);
        $this->assertEquals(json_encode(array(
            'value' => 'bar',
            'label' => 'bar',
        )), $form->getViewData());

        $this->assertEquals('bar', $form->getData());
        $this->assertEquals(json_encode(array(
            'value' => 'bar',
            'label' => 'bar',
        )), $form->getViewData());
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

        $this->assertEquals(array(), $view->vars['choices']);
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
        )), $form->getViewData());

        $this->assertEquals(array('foo'), $form->getData());

        $this->assertEquals(array(
            new ChoiceView('foo', 'foo', 'Foo'),
            new ChoiceView('bar', 'bar', 'Bar'),
            new ChoiceView('ri', 'ri', 'Ri'),
        ), $view->vars['choices']);

        $this->assertEquals(json_encode(array(
            array('value' => 'foo', 'label' => 'Foo'),
            array('value' => 'bar', 'label' => 'Bar')
        )), $view->vars['value']);

        $this->assertEquals('Foo, Bar, ', $view->vars['autocompleter_value']);
    }
}
