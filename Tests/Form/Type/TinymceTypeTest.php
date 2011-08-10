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

class TinymceTypeTest extends TypeTestCase
{
    public function testCulture()
    {
        $form = $this->factory->create('genemu_tinymce');
        $view = $form->createView();
        
        $this->assertEquals('de_DE', $view->get('culture'));
    }
    
    public function testDefaultTheme()
    {
        $form = $this->factory->create('genemu_tinymce');
        $view = $form->createView();
        
        $this->assertEquals('advanced', $view->get('theme'));
    }
    
    public function testOptionTheme()
    {
        $form = $this->factory->create('genemu_tinymce', null, array(
            'theme' => 'simple'
        ));
        $view = $form->createView();
        
        $this->assertEquals('simple', $view->get('theme'));
    }
    
    public function testWidth()
    {
        $form = $this->factory->create('genemu_tinymce', null, array(
            'width' => '80%'
        ));
        $view = $form->createView();
        
        $this->assertEquals('80%', $view->get('width'));
    }
    
    public function testHeight()
    {
        $form = $this->factory->create('genemu_tinymce', null, array(
            'height' => '400px'
        ));
        $view = $form->createView();
        
        $this->assertEquals('400px', $view->get('height'));
    }
    
    public function testScriptUrl()
    {
        $form = $this->factory->create('genemu_tinymce', null, array(
            'script_url' => '/tinymce/tiny_mce.js'
        ));
        $view = $form->createView();
        
        $this->assertEquals('/tinymce/tiny_mce.js', $view->get('script_url'));
    }
    
    public function testSubmitValue()
    {
        $form = $this->factory->create('genemu_tinymce');
        $form->bind('<p>Value of content tinymce</p>');
        
        $this->assertEquals('<p>Value of content tinymce</p>', $form->getData());
    }
    
    public function testSubmitEmptyValue()
    {
        $form = $this->factory->create('genemu_tinymce');
        $form->bind(null);
        
        $this->assertNull($form->getData());
    }    
}