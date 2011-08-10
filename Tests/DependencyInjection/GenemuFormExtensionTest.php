<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\DependencyInjection;

abstract class GenemuFormExtensionTest extends ContainerTest
{
    public function testTinymce()
    {
        $container = $this->createContainerFromFile('full');
        
        $this->assertTrue($container->hasDefinition('genemu_tinymce.form.type'));
        $this->assertEquals('advanced', $container->getParameter('genemu.form.tinymce.theme'));
        $this->assertEquals('/tinymce/tiny_mce.js', $container->getParameter('genemu.form.tinymce.script_url'));
        $this->assertNull($container->getParameter('genemu.form.tinymce.config'));
        $this->assertNull($container->getParameter('genemu.form.tinymce.width'));
        $this->assertNull($container->getParameter('genemu.form.tinymce.height'));
    }
    
    public function testReCaptcha()
    {
        $container = $this->createContainerFromFile('full');
        
        $this->assertTrue($container->hasDefinition('genemu_recaptcha.form.type'));
        $this->assertEquals('clean', $container->getParameter('genemu.form.recaptcha.theme'));
        $this->assertEquals('6Ldv48YSAAAAAFz8xkAnPXEhvckefEy_NaDyjL8j', $container->getParameter('genemu.form.recaptcha.public_key'));
        $this->assertEquals('6Ldv48YSAAAAABGLdVG__UD-JaMeqXMJ6SqV7dZ9', $container->getParameter('genemu.form.recaptcha.private_key'));
        $this->assertEquals('http://api.recaptcha.net', $container->getParameter('genemu.form.recaptcha.server_url'));
        $this->assertEquals('https://api-secure.recaptcha.net', $container->getParameter('genemu.form.recaptcha.server_url_ssl'));
        $this->assertFalse($container->getParameter('genemu.form.recaptcha.use_ssl'));
    }
    
    public function testDoubleList()
    {
        $container = $this->createContainerFromFile('full');
        
        $this->assertTrue($container->hasDefinition('genemu_doublelist.form.type'));
        $this->assertTrue($container->getParameter('genemu.form.doublelist.associated_first'));
        $this->assertEquals('double_list', $container->getParameter('genemu.form.doublelist.class'));
        $this->assertEquals('double_list_select', $container->getParameter('genemu.form.doublelist.class_select'));
        $this->assertEquals('Associated', $container->getParameter('genemu.form.doublelist.label_associated'));
        $this->assertEquals('Unassociated', $container->getParameter('genemu.form.doublelist.label_unassociated'));
    }
    
    public function testJQuerydate()
    {
        $container = $this->createContainerFromFile('full');
        
        $this->assertTrue($container->hasDefinition('genemu_jquerydate.form.type'));
        $this->assertFalse($container->getParameter('genemu.form.jquerydate.image'));
        $this->assertNull($container->getParameter('genemu.form.jquerydate.config'));
    }
}