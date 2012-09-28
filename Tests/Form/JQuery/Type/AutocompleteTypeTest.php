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
use Genemu\Bundle\FormBundle\Form\JQuery\Type\AutocompleteType;

/**
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 */
class AutocompleteTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create(new AutocompleteType('text'));
        $view = $form->createView();

        $this->assertEquals(array(), $view->vars['suggestions']);
        $this->assertEquals('', $form->getViewData());
        $this->assertEquals('', $view->vars['value']);
        $this->assertNull($view->vars['route_name']);
    }

    public function testValue()
    {
        $form = $this->factory->create(new AutocompleteType('text'), null, array(
            'suggestions' => array('Foo', 'Bar')
        ));

        $form->setData('Foo');
        $view = $form->createView();

        $this->assertEquals(array('Foo', 'Bar'), $view->vars['suggestions']);
        $this->assertEquals('Foo', $form->getViewData());
        $this->assertEquals('Foo', $view->vars['value']);
    }

    public function testValueWithAjax()
    {
        $form = $this->factory->create(new AutocompleteType('text'), null, array(
            'route_name' => 'genemu_choice'
        ));

        $form->setData('Foo');
        $view = $form->createView();
        $form->bind('Bar');

        $this->assertEquals(array(), $view->vars['suggestions']);
        $this->assertEquals('Bar', $form->getViewData());

        $this->assertEquals('Bar', $form->getData());
    }
}
