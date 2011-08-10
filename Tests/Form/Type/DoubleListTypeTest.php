<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Type;

use Genemu\Bundle\FormBundle\Tests\Form\TypeTestCase;

/**
 * DoubleListTypeTest
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class DoubleListTypeTest extends TypeTestCase
{
    public function testDefaultClass()
    {
        $form = $this->factory->create('genemu_doublelist');
        $view = $form->createView();
        
        $this->assertEquals('double_list', $view->get('class'));
    }
    
    public function testClass()
    {
        $form = $this->factory->create('genemu_doublelist', null, array(
            'class' => 'test_class'
        ));
        $view = $form->createView();
        
        $this->assertEquals('test_class', $view->get('class'));
    }
    
    public function testDefaultClassSelect()
    {
        $form = $this->factory->create('genemu_doublelist');
        $view = $form->createView();
        
        $this->assertEquals('double_list_select', $view->get('class_select'));
    }
    
    public function testClassSelect()
    {
        $form = $this->factory->create('genemu_doublelist', null, array(
            'class_select' => 'test_class_select'
        ));
        $view = $form->createView();
        
        $this->assertEquals('test_class_select', $view->get('class_select'));
    }
    
    public function testDefaultLabelAssociated()
    {
        $form = $this->factory->create('genemu_doublelist');
        $view = $form->createView();
        
        $this->assertEquals('Associated', $view->get('label_associated'));
    }
    
    public function testLabelAssociated()
    {
        $form = $this->factory->create('genemu_doublelist', null, array(
            'label_associated' => 'Test label'
        ));
        $view = $form->createView();
        
        $this->assertEquals('Test label', $view->get('label_associated'));
    }
    
    public function testDefaultAssociatedFirst()
    {
        $form = $this->factory->create('genemu_doublelist');
        $view = $form->createView();
        
        $this->assertTrue($view->get('associated_first'));
        $this->assertEquals('left', $view->get('float'));
        $this->assertEquals($view->get('id'), $view->get('next'));
        $this->assertEquals($view->get('unassociated')->get('id'), $view->get('previous'));
    }
    
    public function testAssociatedFirst()
    {
        $form = $this->factory->create('genemu_doublelist', null, array(
            'associated_first' => false
        ));
        $view = $form->createView();
        
        $this->assertFalse($view->get('associated_first'));
        $this->assertEquals('right', $view->get('float'));
        $this->assertEquals($view->get('unassociated')->get('id'), $view->get('next'));
        $this->assertEquals($view->get('id'), $view->get('previous'));
    }
    
    public function testValue()
    {
        $form = $this->factory->create('genemu_doublelist');
        $view = $form->createView();
        
        $this->assertNull($view->get('value'));
    }
    
    public function testChoices()
    {
        $form = $this->factory->create('genemu_doublelist', null, array(
            'choices' => array(
                'foo' => 'foo',
                'bar' => 'bar',
                'test' => 'test'
            )
        ));
        $form->bind(array('foo' => 'foo'));
        $view = $form->createView();
        
        $this->assertEquals(array('foo' => 'foo'), $view->get('choices'));
        $this->assertEquals(array('bar' => 'bar', 'test' => 'test'), $view->get('unassociated')->get('choices'));
        $this->assertNull($view->get('value'));
    }
}