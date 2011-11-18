<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class JQueryFileTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_jqueryfile');
        $view = $form->createView();

        $configs = $view->get('configs');

        $this->assertEquals('', $view->get('value'));
        $this->assertFalse($view->get('required'));
        $this->assertEquals(realpath(__DIR__.'/../../Fixtures'), realpath($form->getAttribute('rootDir')));

        $this->assertEquals('/upload', $configs['folder']);
        $this->assertEquals('/swf/uploadify.swf', $configs['uploader']);
        $this->assertEquals('/images/cancel.png', $configs['cancel_img']);
        $this->assertEquals('genemu_upload', $configs['script']);
    }

    public function testConfigs()
    {
        $form = $this->factory->create('genemu_jqueryfile', null, array(
            'configs' => array(
                'folder' => '/images',
                'cancel_img' => '/js/uploadify/cancel.png'
            )
        ));

        $view = $form->createView();

        $configs = $view->get('configs');

        $this->assertFalse($view->get('required'));
        $this->assertEquals('/images', $configs['folder']);
        $this->assertEquals('/swf/uploadify.swf', $configs['uploader']);
        $this->assertEquals('/js/uploadify/cancel.png', $configs['cancel_img']);
        $this->assertEquals('genemu_upload', $configs['script']);
    }

    public function testValue()
    {
        $form = $this->factory->create('genemu_jqueryfile');

        $form->setData('/upload/symfony.png');
        $view = $form->createView();

        $this->assertEquals('/upload/symfony.png', $form->getClientData());
        $this->assertEquals('/upload/symfony.png', $view->get('value'));
    }

    public function testMultipleStringPathValue()
    {
        $form = $this->factory->create('genemu_jqueryfile', null, array(
            'multiple' => true
        ));

        $form->setData('/upload/symfony.png,/upload/symfony.png');
        $view = $form->createView();

        $this->assertEquals('/upload/symfony.png,/upload/symfony.png', $view->get('value'));
    }

    public function testMultipleArrayPathValue()
    {
        $form = $this->factory->create('genemu_jqueryfile', null, array(
            'multiple' => true
        ));

        $form->setData(array('/upload/symfony.png', '/upload/symfony.png'));
        $view = $form->createView();

        $this->assertEquals('/upload/symfony.png,/upload/symfony.png', $view->get('value'));
    }
}
