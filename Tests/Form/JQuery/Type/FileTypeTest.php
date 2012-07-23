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

use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Gd\File\Image;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class FileTypeTest extends TypeTestCase
{
    const FILE_CLASS = 'Symfony\Component\HttpFoundation\File\File';
    const IMAGE_CLASS = 'Genemu\Bundle\FormBundle\Gd\File\Image';

    public function setUp()
    {
        parent::setUp();

        if (!function_exists('gd_info')) {
            $this->markTestSkipped('Gd not installed');
        }
    }

    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_jqueryfile');
        $view = $form->createView();

        $configs = $view->vars['configs'];

        $this->assertEquals('', $view->vars['value']);
        $this->assertFalse($view->vars['required']);
        $this->assertEquals(realpath(__DIR__.'/../../../Fixtures'), realpath($form->getAttribute('rootDir')));

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

        $configs = $view->vars['configs'];

        $this->assertFalse($view->vars['required']);
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

        $this->assertEquals('/upload/symfony.png', $form->getViewData());
        $this->assertEquals('/upload/symfony.png', $view->vars['value']);
    }

    public function testFileValue()
    {
        $form = $this->factory->create('genemu_jqueryfile');

        $form->setData(new File(__DIR__ . '/../../../Fixtures/upload/symfony.png'));

        $data = $form->getNormData();
        $this->assertInstanceOf(self::FILE_CLASS, $data);

        $view = $form->createView();
        $this->assertEquals('/upload/symfony.png', $view->vars['value']);
    }

    public function testImageValue()
    {
        $form = $this->factory->create('genemu_jqueryfile');

        $form->setData(new Image(__DIR__ . '/../../../Fixtures/upload/symfony.png'));

        $data = $form->getNormData();
        $this->assertInstanceOf(self::IMAGE_CLASS, $data);
        $this->assertEquals(160, $data->getWidth());
        $this->assertEquals(134, $data->getHeight());

        $view = $form->createView();
        $this->assertEquals('/upload/symfony.png', $view->vars['value']);
    }

    public function testMultipleStringPathValue()
    {
        $form = $this->factory->create('genemu_jqueryfile', null, array(
            'multiple' => true
        ));

        $form->setData('/upload/symfony.png,/upload/symfony.png');
        $view = $form->createView();

        $this->assertEquals('/upload/symfony.png,/upload/symfony.png', $view->vars['value']);
    }

    public function testMultipleArrayPathValue()
    {
        $form = $this->factory->create('genemu_jqueryfile', null, array(
            'multiple' => true
        ));

        $form->setData(array('/upload/symfony.png', '/upload/symfony.png'));
        $view = $form->createView();

        $this->assertEquals('/upload/symfony.png,/upload/symfony.png', $view->vars['value']);
    }
}
